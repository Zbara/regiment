<?php

namespace App\Command;

use App\Service\ConnectGame;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:game',
    description: 'Add a short description for your command',
)]
class UpdateAuthCommand extends Command
{
    public function __construct(
        private ConnectGame $connectGame
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('params', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->connectGame->authInfo();
    }
}
