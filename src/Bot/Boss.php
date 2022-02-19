<?php

namespace App\Bot;

use App\Repository\RegimentUsersRepository;
use App\Service\ConnectGame;
use App\Service\Redis;
use App\Service\Vkontakte;
use Doctrine\ORM\EntityManagerInterface;

class Boss
{
    private EntityManagerInterface $entityManager;
    private Vkontakte $vkontakte;
    private RegimentUsersRepository $regimentUsersRepository;
    private Redis $redis;
    private Game $game;

    public function __construct(
        EntityManagerInterface  $entityManager,
        RegimentUsersRepository $regimentUsersRepository,
        Vkontakte               $vkontakte,
        Redis                   $redis,
        Game             $game
    )
    {
        $this->entityManager = $entityManager;
        $this->vkontakte = $vkontakte;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->game = $game;
        $this->redis = $redis;
    }

    public function handle(): array
    {
        $this->game->authGame();

        $requests = [];
        $requests[] = ["method" => 'raid.update', "params" => []];

        $update  = $this->game->generateQuery("action", "requests=" . json_encode($requests));


        if(isset($update['result'])){
            if($update['player']['raid']['health'] <= 0){
                dump( 'Босс убит');
                $requests = [];
                $requests[] = ["method" => 'raid.finish', "params" => []];
                $finish = $this->game->generateQuery("action", "requests=" . json_encode($requests));
            }
        }




        die;

    }
}
