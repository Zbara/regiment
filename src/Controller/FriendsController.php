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
        return $this->render('friends/information.html.twig');
    }

    #[Route('/friends/extensions', name: 'friends-extensions')]
    public function extensions(Request $request): Response
    {
        return $this->render('friends/extensions.html.twig');
    }

    #[Route('/friends/search', name: 'friends-search')]
    public function search(Request $request, Friends $friends): Response
    {
        return $this->render('friends/search.html.twig');
    }

    #[Route('/friends/get', name: 'friends-get')]
    public function index(Request $request, Friends $friends): Response
    {
        if ($userId = $request->get('userId', 0)) {
            return $this->json($friends->helper($userId));
        } else  return $this->json(['status' => 0, 'error' => ['messages' => 'Пользователь не найден.']]);
    }

    #[Route('/friends/get/social', name: 'friends-social')]
    public function social(Request $request, Friends $friends): Response
    {
        $userId = $request->get('userId', 0);
        $ownerId = $request->get('ownerId', 0);

        if ($userId and $ownerId) {
            return $this->json($friends->social($userId, $ownerId));
        } else  return $this->json(['status' => 0, 'error' => ['messages' => 'Отсутствуют параметры пользователя.']]);
    }
}
