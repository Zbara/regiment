<?php

namespace App\Service;

use App\Repository\RegimentUsersRepository;
use Doctrine\ORM\EntityManagerInterface;

class Friends
{

    private EntityManagerInterface $entityManager;
    private RegimentUsersRepository $regimentUsersRepository;

    public function __construct(EntityManagerInterface $entityManager, RegimentUsersRepository $regimentUsersRepository)
    {
        $this->entityManager = $entityManager;
        $this->regimentUsersRepository = $regimentUsersRepository;
    }

    public function helper($userId): array
    {
        if($user = $this->regimentUsersRepository->findOneBy(['socId' => $userId])){
            return [
                'status' => 1,
                'data' => $user
            ];
        }
        return ['status' => 0, 'error' => ['messages' => 'Пользователь не найден, ни в базе скрипта ни в игре.']];
    }
}
