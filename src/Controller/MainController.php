<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return new Response('Работает! Symfony prod.');
    }

    #[
        Route('/contract-offer', name: 'contract-offer')
    ]
    public function contract(Security $security): Response
    {
        return $this->render('main/contract-offer.html.twig');
    }
}
