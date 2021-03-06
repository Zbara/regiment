<?php

namespace App\Controller;

use App\Repository\RegimentStatsUsersRepository;
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
        Route('/calculator', name: 'top-calculator')
    ]
    public function calculator(): Response
    {
        return $this->render('top/calculator.html.twig');
    }

    #[
        Route('/top/stats/{id<\d+>}', name: 'top-stats')
    ]
    public function stats(Request $request, int $id, RegimentStatsUsersRepository $statsUsersRepository, RegimentUsersRepository $regimentUsersRepository): Response
    {
        return $this->render('top/stats.html.twig', [
            'stats' => $statsUsersRepository->findBy(['user' => $id],  ['created' => 'DESC']),
            'user' => $regimentUsersRepository->find($id)
        ]);
    }

    #[Route('/top', name: 'top')]
    public function index(Request $request, PaginatorInterface $paginator, RegimentUsersRepository $regimentUsersRepository, Top $top): Response
    {
        $friends = $top->getFriends();

        $users = $paginator->paginate($regimentUsersRepository->findLatest($request->query->get('friends', 'all'), $friends), $request->query->getInt('page', 1), 250, [
            'defaultSortDirection' => 'desc'
        ]);
        $users->setTotalItemCount(2500);


        if($this->isGranted('IS_AUTHENTICATED')){
            $profile = [
                'top' => $regimentUsersRepository->rank($this->getUser()->getVkontakteID()),
                'user' => $regimentUsersRepository->findOneBy(['socId' => $this->getUser()->getVkontakteID()])
            ];
        } else $profile = [];

        return $this->render('top/index.html.twig', [
            'pagination' => $users,
            'update' => $regimentUsersRepository->updateTime(),
            'friends' => $friends,
            'level' => RegimentLibs::LEVEL,
            'profile' => $profile
        ]);
    }
}
