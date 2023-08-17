<?php

namespace App\Command;

use App\Repository\RegimentUsersRepository;
use App\Service\ConnectGame;
use App\Service\Friends;
use App\Service\RegimentLibs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'zbara:update-users',
    description: 'Add a short description for your command',
)]
class ZbaraUpdateUsersCommand extends Command
{
    public function __construct(
        private RegimentUsersRepository $regimentUsersRepository,
        private Friends                 $friends,
        private ConnectGame             $connectGame,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users =  $this->regimentUsersRepository->findAll();

        foreach ($users as $user){
            $user = $this->connectGame->generateQuery("action", "requests=" . json_encode(
                    [
                        ["method" => 'friends.view', "params" => ["friend" => $user->getId()]]
                    ])
            );
            $userId = $user->getId();

            if (isset($user['result'])) {

                if ($user['result'] == 'ok') {
                    $this->friends->update($user['friends'][$userId], $userId);
                }

            } elseif (in_array($user['descr'], ['session expired', 'failed authorization'])) {
                return Command::FAILURE;
            }
        }
        return Command::SUCCESS;
    }
}
