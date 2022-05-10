<?php

namespace App\Service;

use App\Entity\UsersToken;
use App\Repository\AdsRepository;
use App\Repository\RegimentUsersRepository;
use App\Repository\UserRepository;
use App\Repository\UsersTokenRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class Scanner
{
    private DataResponse $dataResponse;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private UsersTokenRepository $usersTokenRepository;
    private RegimentUsersRepository $usersRepository;

    public function __construct(
        DataResponse           $dataResponse,
        EntityManagerInterface $entityManager,
        Security $security,
        UsersTokenRepository $usersTokenRepository,
        RegimentUsersRepository $usersRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->dataResponse = $dataResponse;
        $this->security = $security;
        $this->usersTokenRepository = $usersTokenRepository;
        $this->usersRepository = $usersRepository;
    }


    public function getUsers($users_ids): array
    {
        $users = [];

        foreach ($this->usersRepository->getUsers(explode(',' , $users_ids)) as $user){
            $users[] = [
                'id' => $user->getId(),
                'platform_id' => $user->getSocId(),
                'social' => [
                    'photo_50' => $user->getPhoto50(),
                    'last_name' =>  $user->getLastName(),
                    'first_name' =>  $user->getFirstName()
                ],
                'level' => $user->getLevel(),
                'xp' => $user->getExperience(),
                'sut' => $user->getSut(),
                'totalDamage' => $user->getTotalDamage(),
                'usedTalents' => $user->getUsedTalents(),
                'loginTime' => $user->getLoginTime(),
                'achievements' => $user->getAchievements()
            ];
        }
        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
            'users' => $users
        ]);
    }


    public function addedToken(string $access_token): array
    {
        $user = $this->usersTokenRepository->findBy(['id' => $this->security->getUser()]);

        if(count($user) === 0){
            $token = new UsersToken();
            $token->setUser($this->security->getUser())
                ->setAccessToken($access_token)
                ->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($token);
            $this->entityManager->flush();

            return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                'messages' => 'Токен успешно добавлен.'
            ]);
        }
        return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'Токен уже добавлен!.');
    }
}
