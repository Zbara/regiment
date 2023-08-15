<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthProvider implements OAuthAwareUserProviderInterface, UserProviderInterface, AccountConnectorInterface
{

    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface|User|null
    {
        $user = $this->userRepository->findOneBy(['vkontakteID' => (int) $response->getUsername()]);

        if (null === $user) {
            $user = new User();
            $user->setEmail(uniqid('', true) . '@zbara.dev');
            $user->setVkontakteID($response->getUsername());
            $user->setPassword(md5(uniqid('', true)));
            $user->setFirstName($response->getFirstName());
            $user->setLastName($response->getLastName());
            $user->setPhotoMedium($response->getProfilePicture());
            $user->setScreenName($response->getNickname());
        }
        $user->setAccessToken($response->getAccessToken());
        $user->setUpdateTime(time());

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    #[Pure]
    public function loadUserByUsername($username)
    {
    }

    #[Pure]
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->loadUserByUsername($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass($class): bool
    {
        return true;
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        // noop
    }
}
