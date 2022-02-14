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
    var int $appId = 8063650;
    var int $game_login;
    var string $game_token;
    var int $current_time;
    var mixed $secret = null;
    var mixed $game_key = null;
    var int $last_rnd = 0;

    private EntityManagerInterface $entityManager;
    private RegimentUsersRepository $regimentUsersRepository;
    private Environment $environment;
    private Vkontakte $vkontakte;
    private Redis $redis;
    private UsersScriptRepository $usersScriptRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RegimentUsersRepository $regimentUsersRepository,
        Environment $environment,
        Vkontakte $vkontakte,
        Redis $redis,
        UsersScriptRepository $usersScriptRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->environment = $environment;
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
        $this->usersScriptRepository = $usersScriptRepository;
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

        if(is_null($this->regimentUsersRepository->getLastId($userId))) {
            if ($this->authInfo()) {
                $requests = [];
                $requests[] = ["method" => 'friends.view', "params" => ["friend" => $userId]];

                $user = $this->generateQuery("action", "requests=" . json_encode($requests));

                if (isset($user['result'])) {
                    if ($user['result'] == 'ok') {
                        return $this->informationSuccess($user['friends'][$userId], $userId);
                    }
                    $this->redis->delete(['authParams']);
                }
            }
        }
        if ($user = $this->regimentUsersRepository->findOneBy([
            'socId' => $userId
        ])) {
            return $this->informationError($user, $userId);
        }
        return ['status' => 0, 'error' => ['messages' => 'Пользователь не найден.']];
    }

    private function userLocal($ownerId){
        $user = $this->usersScriptRepository->findOneBy(['platformId' => (int) $ownerId]);

        if (null === $user) {
            $users  = $this->vkontakte->getApi('https://api.vk.com/method/users.get', [
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
                    'platform_id' => (int) $userId,
                    'level' => $data['static_resources']['level'],
                    'sut' => $data['static_resources']['sut'],
                    'totalDamage' => $data['achievements']['total_damage'],
                    'usedTalents' => $data['static_resources']['used_talents'],
                    'loginTime' => $data['time_resources']['login_time'],
                    'achievements' => $data['achievements']
                ],
                'source' => 'game',
                'messages' => ''
            ]];
    }

    private function update($data, $userId)
    {
        $user = $this->regimentUsersRepository->findOneBy(['socId' => (int)$userId]);

        if (null === $user) {
            $users  = $this->vkontakte->getApi('https://api.vk.com/method/users.get', [
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
                'messages' => ''
            ]];
    }

    public function authInfo()
    {
        if (empty($this->redis->getValue('authParams'))) {

            $appInfo = $this->vkontakte->getApi('https://api.vk.com/method/apps.getEmbeddedUrl', [
                'app_id' => $this->appId,
                'v' => '5.136',
                'access_token' => $_ENV['ACCESS_TOKEN']
            ]);

            if (isset($appInfo['response']['view_url'])) {
                $app = $this->vkontakte->getApi($appInfo['response']['view_url'], [], 'object', false, 'GET');

                preg_match('/\{"api_url"(.+?)}/', $app->getContent(), $token);

                if (isset($token['0'])) {
                    $appGame = $this->vkontakte->getApi('https://vk.regiment.bravegames.ru/frame?' . http_build_query(json_decode($token['0'], 1)), [], 'object', false, 'GET');

                    if ($appGame->getContent()) {
                        preg_match('/\window.game_login = (0|[1-9][0-9]*)/', $appGame->getContent(), $game_login);
                        preg_match('/\window.game_token = "(.+?)"/', $appGame->getContent(), $game_token);
                        preg_match('/\window.current_time = (0|[1-9][0-9]*)/', $appGame->getContent(), $current_time);

                        $this->game_login = (int)$game_login[1];
                        $this->current_time = (int)$current_time[1];
                        $this->game_token = $game_token[1];

                        $user = $this->generateQuery("init", "friends={}");

                        if (isset($user['secret'])) {
                            $this->secret = $user['secret'];
                            $this->game_key = $user['key'];

                            $auth = [
                                'game_login' => $this->game_login,
                                'game_token' => $this->game_token,
                                'current_time' => $this->current_time,
                                'secret' => $this->secret,
                                'game_key' => $this->game_key
                            ];

                            $this->redis->setValue('authParams', $auth, 1800, 1);

                            return $auth;
                        }
                    }
                }
            }
        } else return $this->setAuthInfo();
    }

    private function setAuthInfo(): array
    {
        $auth = $this->redis->getValue('authParams', 1);

        foreach ($auth as $key => $item) {
            $this->$key = $item;
        }

        return $auth;
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

        if ($method == "init") {
            $hash = md5($str);
        } else $hash = md5($this->secret . $str . $this->secret);

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
        try {
            $client = HttpClient::create([
                    'headers' => [
                        'User-Agent' => 'VKAndroidApp/6.54-9332 (Android 11; SDK 30; armeabi-v7a; samsung SM-G970F; ru; 2280x1080)',
                        'Origin' => 'https://vk.regiment.bravegames.ru',
                        'Game-key' => $this->game_key,
                        'Game-check' => md5($sign),
                    ],
//                    'proxy' => 'http://:@127.0.0.1:8888',
//                    'verify_peer' => false,
//                    'verify_host' => false,
                ]
            );
            $response = $client->request('POST', 'https://' . $url, ['body' => $this->compress($data)]);

            if (Response::HTTP_OK === $response->getStatusCode()) {
                return json_decode(rawurldecode(gzuncompress(base64_decode($response->getContent()))), 1);
            }
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return false;
        } catch (DecodingExceptionInterface $e) {
            return $e->getMessage();
        }
    }
}
