<?php

namespace App\Controller;

use App\Repository\UsersTokenRepository;
use App\Service\Scanner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScannerController extends AbstractController
{
    #[Route('/scanner/authorized', name: 'scanner-authorized')]
    public function index(UsersTokenRepository $usersTokenRepository): Response
    {
        if ($usersTokenRepository->findOneBy(['user' => $this->getUser()])) {
            return $this->redirectToRoute('scanner-friends');
        }
        return $this->render('scanner/authorized.html.twig');
    }

    #[Route('/scanner/friends', name: 'scanner-friends')]
    public function friends(UsersTokenRepository $usersTokenRepository): Response
    {
        $user = $usersTokenRepository->findOneBy(['user' => $this->getUser()]);

        if (isset($user)) {
            return $this->render('scanner/friends.html.twig', [
                'user' => $user
            ]);
        }
        return $this->redirectToRoute('scanner-authorized');
    }

    #[Route('/scanner/api/setToken', name: 'scanner-set-token')]
    public function addToken(Request $request, Scanner $scanner): Response
    {
        if ($token = $request->get('access_token')) {
            return $this->json($scanner->addedToken($token));
        }
        return $this->json(['status' => 0, 'error' => ['messages' => 'Ошибка!']]);
    }

    #[Route('/scanner/api/getUsers', name: 'scanner-users')]
    public function getUsers(Request $request, Scanner $scanner): Response
    {
        if ($users_uid = $request->get('users_ids')) {
            return $this->json($scanner->getUsers($users_uid));
        }
        return $this->json(['status' => 0, 'error' => ['messages' => 'Ошибка!']]);
    }
}
