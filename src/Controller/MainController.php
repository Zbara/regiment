<?php

namespace App\Controller;

use App\Repository\StatsLogsVisitRepository;
use App\Repository\UsersScriptRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
//        if ($security->isGranted('ROLE_USER')) {
//            return $this->redirectToRoute('top');
//        }
        return $this->render('main/index.html.twig');
    }

    #[
        Route('/contract-offer', name: 'contract-offer')
    ]
    public function contract(Security $security): Response
    {
        return $this->render('main/contract-offer.html.twig');
    }

    #[
        Route('/stats', name: 'main-stats')
    ]
    public function stats(UsersScriptRepository $usersScriptRepository, StatsLogsVisitRepository $logsVisitRepository): Response
    {
        return $this->render('main/stats.twig', [
            'user_all' => $usersScriptRepository->getCount('all'),
            'user_day' => $usersScriptRepository->getCount('day'),
            'visit_all' => $logsVisitRepository->getCount('all'),
            'visit_day' => $logsVisitRepository->getCount('day')
        ]);
    }
}
