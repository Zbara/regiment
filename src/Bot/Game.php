<?php

namespace App\Bot;

use App\Service\Encrypt;
use App\Service\Redis;
use App\Service\Vkontakte;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Game
{
    var int $appId = 8063650;
    var string $accessToken;
    var int $game_login;
    var string $game_token;
    var int $current_time;
    var mixed $secret = null;
    var mixed $game_key = null;
    var int $last_rnd = 0;

    private Vkontakte $vkontakte;
    private Redis $redis;
    private Encrypt $encrypt;
    /**
     * @var false|mixed|string
     */
    private mixed $user;

    public function __construct(Vkontakte $vkontakte, Redis $redis, Encrypt $encrypt)
    {
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
        $this->encrypt = $encrypt;
    }

    public function getUser(){
        return $this->redis->getValue('playerBot', 1);
    }

    public function authGame()
    {
        if (empty($this->redis->getValue('authParamsBot', 1))) {
            $appInfo = $this->vkontakte->getApi('https://api.vk.com/method/apps.getEmbeddedUrl', [
                'app_id' => $this->appId,
                'v' => '5.161',
                'access_token' => $_ENV['ACCESS_TOKEN_BOT']
            ]);

            if (isset($appInfo['response'])) {
                $app = $this->vkontakte->getApi($appInfo['response']['view_url'], [], 'object', false, 'GET');

                preg_match('/\{"api_url"(.+?)}/', $app, $token);
                preg_match('/"access_token":"([0-9a-z]*)"/', $token[0], $accessToken);

                if (isset($token['0'])) {
                    $this->accessToken = $accessToken[1];

                    $appGame = $this->vkontakte->getApi('https://vk.regiment.bravegames.ru/frame?' . http_build_query(json_decode($token['0'], 1)), [], 'object', false, 'GET');

                    if ($appGame) {
                        preg_match('/\window.game_login = (0|[1-9][0-9]*)/', $appGame, $game_login);
                        preg_match('/\window.game_token = "(.+?)"/', $appGame, $game_token);
                        preg_match('/\window.current_time = (0|[1-9][0-9]*)/', $appGame, $current_time);

                        $this->game_login = (int)$game_login[1];
                        $this->current_time = (int)$current_time[1];
                        $this->game_token = $game_token[1];

                        $user = $this->generateQuery("init", "friends=" . json_encode($this->getFriends()));

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
                            $this->redis->setValue('authParamsBot', $auth, 3600, 1);
                        }
                    }
                }
            }
        }
        $this->getAuthInfo();
    }

    public function getAuthInfo(): void
    {
        $auth = $this->redis->getValue('authParamsBot', 1);
        $player = $this->redis->getValue('playerBot', 1);



        if (isset($auth)) {
            foreach ($auth as $key => $item) {
                $this->$key = $item;
            }
            if(empty($player)) {
                $player = $this->generateQuery("get", "");

                if (isset($player['result'])) {
                    $this->redis->setValue('playerBot', $player, 3600, 1);
                }
            }
        }
    }

    private function getFriends(): array
    {
        $getAppUsers = $this->vkontakte->getApi('https://api.vk.com/method/friends.getAppUsers', [
            'app_id' => $this->appId,
            'v' => '5.161',
            'access_token' => $this->accessToken
        ]);

        if (isset($getAppUsers['response'])) {
            $areFriends = $this->vkontakte->getApi('https://api.vk.com/method/friends.areFriends', [
                'app_id' => $this->appId,
                'user_ids' => implode(',', $getAppUsers['response']),
                'v' => '5.161',
                'need_sign' => 1,
                'access_token' => $this->accessToken
            ]);

            $users = [];

            if (isset($areFriends['response'])) {
                foreach ($areFriends['response'] as $friend){
                    $users[$friend['user_id']] = $friend['sign'];
                }
                return $users;
            }
        }
        return [];
    }

    public function generateQuery($method, $data)
    {
        $server = "vk.regiment.bravegames.ru/" . $this->game_login . "/" . $this->game_token . "/";

        $str = "ts=" . $this->getTimestamp();
        if ($data !== "") {
            $str .= "&" . $data;
        }
        $rnd = $this->random(1001, 9999);

        while ($rnd == $this->last_rnd) {
            $rnd = $this->random(1001, 9999);
        }

        $this->last_rnd = $rnd;
        $str .= "&rnd=" . $rnd;

        if ($method == "init") {
            $hash = md5($str);
        } else $hash = md5($this->secret . $str . $this->secret);

        $str .= "&sign=" . $hash;

        return $this->request($server . $method, $str, $hash);
    }

    public function request($url, $data, $sign)
    {
        try {
            $client = HttpClient::create([
                    'headers' => [
                        'User-Agent' => 'VKAndroidApp/6.54-9332 (Android 11; SDK 30; armeabi-v7a; samsung SM-G970F; ru; 2280x1080)',
                        'Origin' => 'https://vk.regiment.bravegames.ru',
                        'Game-key' => $this->game_key,
                        'Game-check' => md5($sign),
                        'proxy' => 'http://:@127.0.0.1:8888',
                        'verify_peer' => false,
                        'verify_host' => false,
                    ]
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

    public function compress($str): string
    {
        $str = urlencode($str);

        return $this->encrypt->encode(base64_encode(gzcompress($str, 9)));
    }

    public function getTimestamp(): int
    {
        return $this->current_time;
    }

    public function random($min, $max): int
    {
        return rand($min, $max);
    }
}
