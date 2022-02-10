<?php

namespace App\Service;

use App\Entity\RegimentUsers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpClient\HttpClient;

class Parse
{
    private EntityManagerInterface $entityManager;

    var $game_login = 135057576;
    var $game_token = "eacb78bec65760e7d5fd4373389ab2e1";
    var $current_time = 1644488343;
    var $secret = "Ko7zWN";
    var $game_key = 'Y4zT1AvovM';
    var $last_rnd;
    var $num;
    private mixed $usersVk;

    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
        $this->usersVk = json_decode(file_get_contents(__DIR__ . '/users.json'));
    }

    public function run(InputInterface $input): int
    {
        foreach ($this->usersVk->response as $item) {
            $requests = [];
            $requests[] = ["method" => 'friends.view', "params" => ["friend" => $item]];

            $user = $this->generateQuery("action", "requests=" . json_encode($requests));

            if (isset($user['result'])) {
                if ($user['result'] == 'ok') {
                    $userId = array_key_first($user['friends']);

                    $regiment = new RegimentUsers();
                    $regiment->setSocId($userId)
                        ->setLevel($user['friends'][$userId]['static_resources']['level'])
                        ->setSut($user['friends'][$userId]['static_resources']['sut'])
                        ->setUsedTalents($user['friends'][$userId]['static_resources']['used_talents'])
                        ->setAchievements($user['friends'][$userId]['achievements'])
                        ->setLoginTime($user['friends'][$userId]['time_resources']['login_time'])
                        ->setCreated(time())
                        ->setTotalDamage($user['friends'][$userId]['achievements']['total_damage']);

                    dump($userId);

                    $this->entityManager->persist($regiment);
                    $this->entityManager->flush();
                }
                sleep(1);
            }
        }
        return Command::SUCCESS;
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

        while ($rnd === $this->last_rnd) {
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
                ],
                'proxy' => 'http://:@127.0.0.1:8888',
                'verify_peer' => false,
                'verify_host' => false,
            ]
        );
        $response = $client->request('POST', 'https://' . $url, ['body' => $this->compress($data)]);

        return json_decode(rawurldecode(gzuncompress(base64_decode($response->getContent()))), 1);
    }
}
