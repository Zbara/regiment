<?php

namespace App\Controller;

use App\Repository\RegimentUsersRepository;
use App\Service\Vkontakte;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopController extends AbstractController
{
    #[
        Route('/calculator', name: 'calculator')
    ]
    public function contract(): Response
    {
        return $this->render('top/calculator.html.twig');
    }

    #[Route('/top', name: 'top')]
    public function index(Request $request, PaginatorInterface $paginator, RegimentUsersRepository $regimentUsersRepository, Vkontakte $vkontakte): Response
    {
        $code = 'var counters = API.users.get({"fields": "counters"});'
            . 'var members = API.friends.get({"count": "1000", "offset": 0}).items;'
            . 'var offset = 1000;'
            . 'while (offset < counters[0].counters.friends && (offset + 0) < 10000)'
            . '{'
            . 'members = members + API.friends.get({"count": "1000", "offset": (0 + offset)}).items;'
            . 'offset = offset + 1000;'
            . '};'
            . 'return members;';

        if($this->isGranted("IS_AUTHENTICATED_FULLY")){
            $friends = $vkontakte->getApi('https://api.vk.com/method/execute', [
                'access_token' => $this->getUser()->getAccessToken(),
                'v' => '5.161',
                'code' => $code
            ]);
            $friends = $friends['response'] ?? [];
        } else $friends = [];

        return $this->render('top/index.html.twig', [
            'pagination' => $paginator->paginate($regimentUsersRepository->findLatest($request->query->get('friends', 'all'), $friends), $request->query->getInt('page', 1), 250, [
                'defaultSortDirection' => 'desc'
            ]),
            'update' => $regimentUsersRepository->updateTime(),
            'friends' => $friends
        ]);
    }
}
