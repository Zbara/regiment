<?php

namespace App\Service;

use App\Entity\RegimentUsers;
use App\Repository\RegimentUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Parse
{
    var int $groups;
    var int $members_count;
    private array $membersGroups = [];

    private EntityManagerInterface $entityManager;
    private Vkontakte $vkontakte;
    private RegimentUsersRepository $regimentUsersRepository;
    private ConnectGame $connectGame;
    private Redis $redis;


    public function __construct(
        EntityManagerInterface  $entityManager,
        RegimentUsersRepository $regimentUsersRepository,
        Vkontakte               $vkontakte,
        Redis                   $redis,
        ConnectGame             $connectGame
    )
    {
        $this->entityManager = $entityManager;
        $this->vkontakte = $vkontakte;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->connectGame = $connectGame;
        $this->redis = $redis;
    }

    public function run($params): int
    {
        $this->groups = $params;

        if ($this->groups) {
            if ($this->connectGame->authInfo()) {
                $this->getUsers();
            }
        } else dump('Ошибка при получение id groups');

        return Command::SUCCESS;
    }

    private function getUsers()
    {
        $getById = $this->vkontakte->getApi('https://api.vk.com/method/groups.getById', [
            'group_id' => $this->groups,
            'v' => '5.136',
            'fields' => 'photo_50,members_count',
            'access_token' => $_ENV['ACCESS_TOKEN']
        ]);

        if (isset($getById['response']['0'])) {
            $this->members_count = $getById['response']['0']['members_count'];

            if ($this->members_count > 0) {
                $this->getMembers();
            }
        }
    }

    private function getMembers()
    {
        /** @var  $code */
        $code = 'var members = API.groups.getMembers({"group_id": ' . $this->groups . ', "v": "5.103", "sort": "id_asc", "fields": "photo_50", "count": "1000", "offset": ' . count($this->membersGroups) . '}).items;'
            . 'var offset = 1000;'
            . 'while (offset < 25000 && (offset + ' . count($this->membersGroups) . ') < ' . $this->members_count . ')'
            . '{'
            . 'members = members + API.groups.getMembers({"group_id": ' . $this->groups . ', "v": "5.103", "sort": "id_asc", "fields": "photo_50", "count": "1000", "offset": (' . count($this->membersGroups) . ' + offset)}).items;'
            . 'offset = offset + 1000;'
            . '};'
            . 'return members;';

        $execute = $this->vkontakte->getApi('https://api.vk.com/method/execute', [
            'v' => '5.136',
            'code' => $code,
            'access_token' => $_ENV['ACCESS_TOKEN']
        ]);

        if (isset($execute['response'])) {
            $this->membersGroups = array_merge($this->membersGroups, $execute['response']);

            if ($this->members_count > count($this->membersGroups)) {
                $this->getMembers();
            } else $this->setMembers();
        }
    }

    private function setMembers()
    {
        foreach ($this->membersGroups as $item) {
            $requests = [];
            $requests[] = ["method" => 'friends.view', "params" => ["friend" => $item['id']]];

            $user = $this->connectGame->generateQuery("action", "requests=" . json_encode($requests));

            if (isset($user['result'])) {
                if ($user['result'] == 'ok') {
                    $userId = array_key_first($user['friends']);

                    $this->saveUsers($user['friends'][$userId], $userId, $item);
                }
            } elseif (in_array($user['descr'], ['session expired', 'failed authorization'])) {
                $this->redis->delete(['authParams']);
            }
            usleep(700000);
        }
    }

    private function saveUsers($data, $userId, $vkInfo)
    {
        $user = $this->regimentUsersRepository->findOneBy(['socId' => (int)$userId]);

        if (null === $user) {
            $user = new RegimentUsers();
            $user->setSocId($userId)
                ->setCreated(time())
                ->setPhoto50($vkInfo['photo_50'])
                ->setLastName($vkInfo['last_name'])
                ->setFirstName($vkInfo['first_name']);
        }
        $user->setLevel($data['static_resources']['level'])
            ->setSut($data['static_resources']['sut'])
            ->setUsedTalents($data['static_resources']['used_talents'])
            ->setAchievements($data['achievements'])
            ->setLoginTime($data['time_resources']['login_time'])
            ->setUpdateTime(time())
            ->setTotalDamage($data['achievements']['total_damage']);

        dump($user->getId() ? $userId . ' update' : $userId . 'created');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
