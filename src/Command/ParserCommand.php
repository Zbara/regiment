<?php

namespace App\Command;


use App\Service\Parse;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:parse',
    description: 'Add a short description for your command',
)]
//'{"login":135057576,"token":"6ecee60b636c68e06f1775000004b5df","time":1644521056,"secret":"romNW3","key":"7y8aee5G2C","groups":190682495}'
class ParserCommand extends Command
{

    public function __construct(
        private Parse $parse
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
        $params = json_decode($input->getArgument('params'));

        return $this->parse->run($params);
    }
}
