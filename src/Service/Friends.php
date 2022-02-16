<?php

namespace App\Service;

use App\Entity\RegimentUsers;
use App\Entity\UsersScript;
use App\Repository\RegimentUsersRepository;
use App\Repository\UsersScriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Twig\Environment;

class Friends
{
    private EntityManagerInterface $entityManager;
    private RegimentUsersRepository $regimentUsersRepository;
    private Environment $environment;
    private Vkontakte $vkontakte;
    private Redis $redis;
    private UsersScriptRepository $usersScriptRepository;
    private ConnectGame $connectGame;

    public function __construct(
        EntityManagerInterface  $entityManager,
        RegimentUsersRepository $regimentUsersRepository,
        Environment             $environment,
        Vkontakte               $vkontakte,
        Redis                   $redis,
        UsersScriptRepository   $usersScriptRepository,
        ConnectGame             $connectGame
    )
    {
        $this->entityManager = $entityManager;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->environment = $environment;
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
        $this->usersScriptRepository = $usersScriptRepository;
        $this->connectGame = $connectGame;

    }

    public function helper($userId): array
    {
        if ($user = $this->regimentUsersRepository->findOneBy([
            'socId' => $this->vkontakte->getUserId($userId, $_ENV['ACCESS_TOKEN'])
        ])) {
            return [
                'status' => 1,
                'result' => [
                    'data' => $user,
                    'html' => $this->environment->render('friends/get.html.twig', [
                        'user' => $user
                    ])
                ]];
        }
        return ['status' => 0, 'error' => ['messages' => 'Пользователь не найден.']];
    }

    public function social($userId, $ownerId): array
    {
        $this->userLocal($ownerId);

        if (is_null($this->regimentUsersRepository->getLastId($userId))) {
            if ($this->connectGame->authInfo()) {
                $requests = [];
                $requests[] = ["method" => 'friends.view', "params" => ["friend" => $userId]];

                $user = $this->connectGame->generateQuery("action", "requests=" . json_encode($requests));

                if (isset($user['result'])) {
                    if ($user['result'] == 'ok') {
                        return $this->informationSuccess($user['friends'][$userId], $userId);
                    }
                } elseif (in_array($user['descr'], ['session expired', 'failed authorization'])) {
                    $this->redis->delete(['authParams']);
                }
            }
        }
        if ($user = $this->regimentUsersRepository->findOneBy([
            'socId' => $userId
        ])) {
            return $this->informationError($user, $userId);
        }
        return ['status' => 0, 'error' => ['messages' => 'Игрок не найден. Запускал ли он Храбрый Полк?']];
    }

    private function userLocal($ownerId)
    {
        $user = $this->usersScriptRepository->findOneBy(['platformId' => (int)$ownerId]);

        $this->regimentUsersRepository->getLevelRank();

        if (null === $user) {
            $users = $this->vkontakte->getApi('https://api.vk.com/method/users.get', [
                'v' => '5.136',
                'user_ids' => $ownerId,
                'fields' => 'photo_50',
                'access_token' => $_ENV['ACCESS_TOKEN']
            ]);
            $user = new UsersScript();
            $user->setPlatformId($ownerId)
                ->setCreated(time())
                ->setPhoto50($users['response'][0]['photo_50'] ?? 'https://vk.com/images/camera_100.png')
                ->setLastName($users['response'][0]['last_name'] ?? 'Неизвестно')
                ->setFirstName($users['response'][0]['first_name'] ?? '...');
        }
        $user->setLastTime(time());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    #[ArrayShape(['status' => "int", 'result' => "array"])]
    private function informationSuccess($data, $userId): array
    {
        $this->update($data, $userId);

        return [
            'status' => 1,
            'result' => [
                'data' => [
                    'platform_id' => (int)$userId,
                    'level' => $data['static_resources']['level'],
                    'sut' => $data['static_resources']['sut'],
                    'totalDamage' => $data['achievements']['total_damage'],
                    'usedTalents' => $data['static_resources']['used_talents'],
                    'loginTime' => $data['time_resources']['login_time'],
                    'achievements' => $data['achievements']
                ],
                'source' => 'game',
                'messages' => '',
                'environment' => $_ENV['APP_ENV']
            ]];
    }

    private function update($data, $userId)
    {
        $user = $this->regimentUsersRepository->findOneBy(['socId' => (int)$userId]);

        if (null === $user) {
            $users = $this->vkontakte->getApi('https://api.vk.com/method/users.get', [
                'v' => '5.136',
                'user_ids' => $userId,
                'fields' => 'photo_50',
                'access_token' => $_ENV['ACCESS_TOKEN']
            ]);

            $user = new RegimentUsers();
            $user->setSocId($userId)
                ->setCreated(time())
                ->setPhoto50($users['response'][0]['photo_50'] ?? 'https://vk.com/images/camera_100.png')
                ->setLastName($users['response'][0]['last_name'] ?? 'Неизвестно')
                ->setFirstName($users['response'][0]['first_name'] ?? '...');
        }
        $user->setLevel($data['static_resources']['level'])
            ->setSut($data['static_resources']['sut'])
            ->setUsedTalents($data['static_resources']['used_talents'])
            ->setAchievements($data['achievements'])
            ->setLoginTime($data['time_resources']['login_time'])
            ->setUpdateTime(time())
            ->setTotalDamage($data['achievements']['total_damage']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    #[ArrayShape(['status' => "int", 'result' => "array"])]
    private function informationError(RegimentUsers $data, $userId): array
    {
        return [
            'status' => 1,
            'result' => [
                'data' => [
                    'platform_id' => $data->getSocId(),
                    'level' => $data->getLevel(),
                    'sut' => $data->getSut(),
                    'totalDamage' => $data->getTotalDamage(),
                    'usedTalents' => $data->getUsedTalents(),
                    'loginTime' => $data->getLoginTime(),
                    'achievements' => $data->getAchievements()
                ],
                'source' => 'local',
                'messages' => '',
                'environment' => $_ENV['APP_ENV']
            ]];
    }
}
