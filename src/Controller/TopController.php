<?php

namespace App\Controller;

use App\Repository\RegimentUsersRepository;
use App\Service\RegimentLibs;
use App\Service\Top;
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
    public function index(Request $request, PaginatorInterface $paginator, RegimentUsersRepository $regimentUsersRepository, Top $top): Response
    {
        $friends = $top->getFriends();

        return $this->render('top/index.html.twig', [
            'pagination' => $paginator->paginate($regimentUsersRepository->findLatest($request->query->get('friends', 'all'), $friends), $request->query->getInt('page', 1), 250, [
                'defaultSortDirection' => 'desc'
            ]),
            'update' => $regimentUsersRepository->updateTime(),
            'friends' => $friends,
            'level' => RegimentLibs::LEVEL
        ]);
    }
}
