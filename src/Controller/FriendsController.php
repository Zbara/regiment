<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendsController extends AbstractController
{
    #[Route('/friends', name: 'friends')]
    public function index(): Response
    {
        return $this->render('friends/index.html.twig', [
            'controller_name' => 'FriendsController',
        ]);
    }
}
