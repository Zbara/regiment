<?php

namespace App\Command;

use App\Entity\RegimentUsers;
use App\Repository\RegimentUsersRepository;
use App\Service\Parse;
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
    name: 'app:level:update',
    description: 'Add a short description for your command',
)]
class UpdateXpCommand extends Command
{

    public function __construct(
        private RegimentUsersRepository $regimentUsersRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       $users =  $this->regimentUsersRepository->findAll();

        foreach ($users as $user){
            $level = $user->getLevel();

            if($level > 1){
                $user->setExperience(RegimentLibs::LEVEL[$level - 1]);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}
