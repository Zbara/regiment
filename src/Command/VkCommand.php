<?php

namespace App\Command;


use App\Repository\RegimentUsersRepository;
use App\Service\Parse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:vk',
    description: 'Add a short description for your command',
)]
class VkCommand extends Command
{

    private mixed $usersVk;

    public function __construct(
        private Parse $parse,
        private EntityManagerInterface $entityManager,
        private RegimentUsersRepository $regimentUsersRepository
    ) {
        $this->usersVk = json_decode(file_get_contents(__DIR__ . '/users.json'));

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->usersVk->response as $item) {
            $user = $this->regimentUsersRepository->findOneBy(['socId' => $item->id]);

            if($user){

                $user->setFirstName($item->first_name)
                    ->setLastName($item->last_name)
                    ->setPhoto50($item->photo_50);

                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}
