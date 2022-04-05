<?php

namespace App\Service;

use App\Entity\RegimentStatsUsers;
use App\Entity\RegimentUsers;
use App\Entity\UsersScript;
use App\Repository\RegimentStatsUsersRepository;
use App\Repository\RegimentUsersRepository;
use App\Repository\UsersScriptRepository;
use App\Response\DataResponse;
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
    private DataResponse $dataResponse;
    private Libs $libs;
    private AdsService $ads;
    private Clan $clan;
    private RegimentStatsUsersRepository $regimentStatsUsersRepository;

    public function __construct(
        EntityManagerInterface       $entityManager,
        RegimentUsersRepository      $regimentUsersRepository,
        Environment                  $environment,
        Vkontakte                    $vkontakte,
        Redis                        $redis,
        UsersScriptRepository        $usersScriptRepository,
        ConnectGame                  $connectGame,
        DataResponse                 $dataResponse,
        Libs                         $libs,
        AdsService                   $adsService,
        Clan                         $clan,
        RegimentStatsUsersRepository $regimentStatsUsersRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->environment = $environment;
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
        $this->usersScriptRepository = $usersScriptRepository;
        $this->connectGame = $connectGame;
        $this->dataResponse = $dataResponse;
        $this->libs = $libs;
        $this->ads = $adsService;
        $this->clan = $clan;
        $this->regimentStatsUsersRepository = $regimentStatsUsersRepository;
    }

    public function helper($userId): array
    {
        if ($user = $this->regimentUsersRepository->findOneBy([
            'socId' => $this->vkontakte->getUserId($userId, $_ENV['ACCESS_TOKEN'])
        ])) {
            return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                'data' => $user,
                'html' => $this->environment->render('friends/get.html.twig', [
                    'user' => $user
                ])
            ]);
        }
        return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'Игрок не найден. Запускал ли он Храбрый Полк?');
    }

    public function social($userId, $ownerId): array
    {
        $this->userLocal($ownerId);

        if (is_null($this->regimentUsersRepository->getLastId($userId))) {
            if ($this->connectGame->getAuthInfo()) {
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

        return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'Игрок не найден. Запускал ли он Храбрый Полк?');
    }

    private function userLocal($ownerId)
    {
        $user = $this->usersScriptRepository->findOneBy(['platformId' => (int)$ownerId]);

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

        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
            'data' => [
                'platform_id' => (int)$userId,
                'level' => $data['static_resources']['level'],
                'xp' => $data['experiences']['experience'],
                'sut' => $data['static_resources']['sut'],
                'clan' => $this->clan->info($userId),
                'totalDamage' => $data['achievements']['total_damage'],
                'usedTalents' => $data['static_resources']['used_talents'],
                'loginTime' => $data['time_resources']['login_time'],
                'achievements' => $data['achievements'],
                'top' => $this->regimentUsersRepository->rank($userId),
            ],
            'source' => 'game',
            'messages' => $this->ads->user(),
            'library' => RegimentLibs::libs()
        ]);
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
            ->setExperience($data['experiences']['experience'])
            ->setUpdateTime(time())
            ->setTotalDamage($data['achievements']['total_damage']);

        $user->addRegimentStatsUser($this->updateStatsDay($user));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


    public function updateStatsDay(RegimentUsers $users): ?RegimentStatsUsers
    {
        $user = $this->regimentStatsUsersRepository->findOneBy(['created' => new \DateTime(), 'user' => $users->getId()]);

        if (null === $user) {
            $user = new RegimentStatsUsers();
            $user->setUser($users)
                ->setCreated(new \DateTime());
        }
        $user->setExperience($users->getExperience())
            ->setLevel($users->getLevel())
            ->setTotalDamage($users->getTotalDamage())
            ->setUsedTalents($users->getUsedTalents())
            ->setSut($users->getSut())
            ->setAchievements($users->getAchievements())
            ->setUpdateTime(time());

        return $user;
    }


    #[ArrayShape(['status' => "int", 'result' => "array"])]
    private function informationError(RegimentUsers $data): array
    {
        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
            'data' => [
                'platform_id' => $data->getSocId(),
                'level' => $data->getLevel(),
                'xp' => $data->getExperience(),
                'sut' => $data->getSut(),
                'clan' => $this->clan->info($data->getSocId()),
                'totalDamage' => $data->getTotalDamage(),
                'usedTalents' => $data->getUsedTalents(),
                'loginTime' => $data->getLoginTime(),
                'achievements' => $data->getAchievements(),
                'top' => $this->regimentUsersRepository->rank($data->getSocId()),
            ],
            'source' => 'local',
            'messages' => $this->ads->user(),
            'library' => RegimentLibs::libs()
        ]);
    }
}
