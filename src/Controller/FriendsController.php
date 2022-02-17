<?php

namespace App\Controller;

use App\Response\DataResponse;
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
    public function index(Request $request, Friends $friends, DataResponse $dataResponse): Response
    {
        if ($userId = $request->get('userId', 0)) {
            return $this->json($friends->helper($userId));
        }
        return $this->json($dataResponse->error(0, 'Игрок не найден.'));
    }

    #[Route('/friends/get/social', name: 'friends-social')]
    public function social(Request $request, Friends $friends, DataResponse $dataResponse): Response
    {
        $userId = $request->get('userId', null);
        $ownerId = $request->get('ownerId', null);






        if (isset($userId) and isset($ownerId)) {
            return $this->json($friends->social($userId, $ownerId));
        }
        return $this->json($dataResponse->error(0, 'Отсутствуют параметры пользователя.'));
    }
}
