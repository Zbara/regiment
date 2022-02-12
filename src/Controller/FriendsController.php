<?php

namespace App\Controller;

use App\Service\Friends;
use App\Service\Vkontakte;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendsController extends AbstractController
{
    #[Route('/friends/information', name: 'friends-main')]
    public function information(Request $request, Friends $friends): Response
    {
        return  $this->render('friends/information.html.twig');
    }

    #[Route('/friends/search', name: 'friends-search')]
    public function search(Request $request, Friends $friends): Response
    {
        return  $this->render('friends/search.html.twig');
    }

    #[Route('/friends/get', name: 'friends-get')]
    public function index(Request $request, Friends $friends): Response
    {
        if ($userId = $request->get('userId', 0)) {
            return $this->json($friends->helper($userId));
        } else  return $this->json(['status' => 0, 'error' => ['messages' => 'Пользователь не найден.']]);
    }
}
