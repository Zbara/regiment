<?php

namespace App\Service;

use App\Repository\RegimentUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class Friends
{

    private EntityManagerInterface $entityManager;
    private RegimentUsersRepository $regimentUsersRepository;
    private Environment $environment;
    private Vkontakte $vkontakte;

    public function __construct(EntityManagerInterface $entityManager, RegimentUsersRepository $regimentUsersRepository, Environment $environment, Vkontakte $vkontakte)
    {
        $this->entityManager = $entityManager;
        $this->regimentUsersRepository = $regimentUsersRepository;
        $this->environment = $environment;
        $this->vkontakte = $vkontakte;
    }

    public function helper($userId): array
    {
        if ($user = $this->regimentUsersRepository->findOneBy([
            'socId' => $this->vkontakte->getUserId($userId, $_ENV['ACCESS_TOKEN'])
        ])) {
            return [
                'status' => 1,
                'result' => [
                    'data' => $user,
                    'html' => $this->environment->render('friends/get.html.twig', [
                        'user' => $user
                    ])
                ]];
        }
        return ['status' => 0, 'error' => ['messages' => 'Пользователь не найден.']];
    }
}
