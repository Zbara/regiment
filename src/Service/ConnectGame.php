<?php

namespace App\Service;

use App\Repository\RegimentUsersRepository;
use App\Repository\UsersScriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ConnectGame
{
    var int $appId = 8016542;
    var int $game_login;
    var string $game_token;
    var int $current_time;
    var mixed $secret = null;
    var mixed $game_key = null;
    var int $last_rnd = 0;

    private Vkontakte $vkontakte;
    private Redis $redis;
    private Encrypt $encrypt;

    public function __construct(Vkontakte $vkontakte, Redis $redis, Encrypt $encrypt)
    {
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
        $this->encrypt = $encrypt;
    }

    public function authInfo(): bool|array|null
    {
        if(empty($this->redis->getValue('authParams', 1))) {
            $appInfo = $this->vkontakte->getApi('https://api.vk.com/method/apps.getEmbeddedUrl', [
                'app_id' => $this->appId,
                'v' => '5.136',
                'access_token' => $_ENV['ACCESS_TOKEN']
            ]);

            if (isset($appInfo['response']['view_url'])) {
                $app = $this->vkontakte->getApi($appInfo['response']['view_url'], [], 'object', false, 'GET');

                preg_match('/\{"api_url"(.+?)}/', $app, $token);

                if (isset($token['0'])) {
                    $appGame = $this->vkontakte->getApi('https://vk.regiment.bravegames.ru/frame?' . http_build_query(json_decode($token['0'], 1)), [], 'object', false, 'GET');

                    if ($appGame) {
                        preg_match('/\window.game_login = (0|[1-9][0-9]*)/', $appGame, $game_login);
                        preg_match('/\window.game_token = "(.+?)"/', $appGame, $game_token);
                        preg_match('/\window.current_time = (0|[1-9][0-9]*)/', $appGame, $current_time);

                        $this->game_login = (int)$game_login[1];
                        $this->current_time = (int)$current_time[1];
                        $this->game_token = $game_token[1];

                        $user = $this->generateQuery("init", "friends={}");

                        dump($user);

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

                            $this->redis->setValue('authParams', $auth, 3600, 1);

                            return Command::SUCCESS;
                        }
                    }
                }
            }
        }
        dump('ключ активен');

        return Command::FAILURE;
    }


    public function getAuthInfo(): ?array
    {
        $auth = $this->redis->getValue('authParams', 1);

        if (isset($auth)) {
            foreach ($auth as $key => $item) {
                $this->$key = $item;
            }
            return $auth;
        } else return null;
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

        return $this->encrypt->encode(base64_encode(gzcompress($str, 9)));
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
                    'proxy' => 'http://:@185.238.228.183:80',
                    'verify_peer' => false,
                    'verify_host' => false,
                ]
            );
            $response = $client->request('POST', 'https://' . $url, ['body' => $this->compress($data)]);

            if (Response::HTTP_OK === $response->getStatusCode()) {
                return json_decode(rawurldecode(gzuncompress(base64_decode($this->encrypt->decode($response->getContent())))), 1);
            }
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return false;
        } catch (DecodingExceptionInterface $e) {
            return $e->getMessage();
        }
        return false;
    }
}
