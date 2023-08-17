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
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users =  $this->regimentUsersRepository->findAll();

        foreach ($users as $user){

            dump('Start ' . $user->getId());

            $updateStatus = $this->friends->updateLocal($user);

            if($updateStatus) {
                sleep(1);
            } else return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
