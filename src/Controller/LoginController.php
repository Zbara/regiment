<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(Security $security): Response
    {
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('top');
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
