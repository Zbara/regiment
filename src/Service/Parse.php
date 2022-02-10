<?php

namespace App\Service;

use App\Entity\RegimentUsers;
use App\Repository\RegimentUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpClient\HttpClient;

class Parse
{
    private EntityManagerInterface $entityManager;
    private Vkontakte $vkontakte;

    var int $game_login;
    var string $game_token;
    var int $current_time;
    var string $secret;
    var string $game_key;
    var int $last_rnd = 0;
    var int $groups;
    var int $members_count;
    private array $membersGroups = [];
    private RegimentUsersRepository $regimentUsersRepository;

    public function __construct(
        EntityManagerInterface  $entityManager,
        RegimentUsersRepository $regimentUsersRepository,
        Vkontakte               $vkontakte
    )
    {
        $this->entityManager = $entityManager;
        $this->vkontakte = $vkontakte;
        $this->regimentUsersRepository = $regimentUsersRepository;
    }

    public function run($params): int
    {
        $this->setInfo($params);
        $this->getUsers();

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

            $this->getMembers();

            $this->membersGroups = [];
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

            $user = $this->generateQuery("action", "requests=" . json_encode($requests));

            if (isset($user['result'])) {
                if ($user['result'] == 'ok') {
                    $userId = array_key_first($user['friends']);

                    $this->saveUsers($userId, $user['friends'][$userId], $item);
                }
                sleep(1);
            }
        }
    }

    private function updateUsers(RegimentUsers $regiment, $data, $user)
    {
        $regiment->setLevel($data['static_resources']['level'])
            ->setSut($data['static_resources']['sut'])
            ->setUsedTalents($data['static_resources']['used_talents'])
            ->setAchievements($data['achievements'])
            ->setLoginTime($data['time_resources']['login_time'])
            ->setPhoto50($user['photo_50'])
            ->setLastName($user['last_name'])
            ->setFirstName($user['first_name'])
            ->setTotalDamage($data['achievements']['total_damage']);

        $this->entityManager->persist($regiment);
        $this->entityManager->flush();
    }

    private function createdUsers($userId, $data, $user)
    {
        $regiment = new RegimentUsers();
        $regiment->setSocId($userId)
            ->setLevel($data['static_resources']['level'])
            ->setSut($data['static_resources']['sut'])
            ->setUsedTalents($data['static_resources']['used_talents'])
            ->setAchievements($data['achievements'])
            ->setLoginTime($data['time_resources']['login_time'])
            ->setCreated(time())
            ->setPhoto50($user['photo_50'])
            ->setLastName($user['last_name'])
            ->setFirstName($user['first_name'])
            ->setTotalDamage($data['achievements']['total_damage']);

        $this->entityManager->persist($regiment);
        $this->entityManager->flush();
    }

    private function saveUsers($userId, $data, $user)
    {
        /** @var  $regiment */
        $regiment = $this->regimentUsersRepository->findOneBy(['socId' => $userId]);

        if ($regiment) {
            $this->updateUsers($regiment, $data, $user);
        } else $this->createdUsers($userId, $data, $user);

    }

    public function get_current_timestamp(): int
    {
        return $this->current_time;
    }

    public function random_int($min, $max): int
    {
        return rand($min, $max);
    }

    public function generateQuery($method, $data)
    {
        $server = "vk.regiment.bravegames.ru/" . $this->game_login . "/" . $this->game_token . "/";

        $str = "ts=" . $this->get_current_timestamp();
        if ($data !== "") {
            $str .= "&" . $data;
        }
        $rnd = $this->random_int(1001, 9999);

        while ($rnd == $this->last_rnd) {
            $rnd = $this->random_int(1001, 9999);
        }

        $this->last_rnd = $rnd;
        $str .= "&rnd=" . $rnd;

        $hash = md5($this->secret . $str . $this->secret);

        $str .= "&sign=" . $hash;

        return $this->api($server . $method, $str, $hash);
    }

    function compress($str): string
    {
        $str = urlencode($str);

        return base64_encode(gzcompress($str, 9));
    }

    public function api($url, $data, $sign)
    {
        $client = HttpClient::create([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36',
                    'Origin' => 'https://vk.regiment.bravegames.ru',
                    'Game-key' => $this->game_key,
                    'Game-check' => md5($sign),
                ]
            ]
        );
        $response = $client->request('POST', 'https://' . $url, ['body' => $this->compress($data)]);

        return json_decode(rawurldecode(gzuncompress(base64_decode($response->getContent()))), 1);
    }

    private function setInfo($params)
    {

        $this->game_login = $params->login;
        $this->game_token = $params->token;
        $this->current_time = $params->time;
        $this->secret = $params->secret;
        $this->game_key = $params->key;
        $this->groups = $params->groups;
    }
}
