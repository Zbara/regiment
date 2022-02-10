<?php

namespace App\Controller;

use App\Repository\RegimentUsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopController extends AbstractController
{
    #[Route('/top', name: 'top')]
    public function index(Request $request, PaginatorInterface $paginator, RegimentUsersRepository $regimentUsersRepository): Response
    {
        return $this->render('top/index.html.twig', [
            'pagination' => $paginator->paginate($regimentUsersRepository->findLatest(), $request->query->getInt('page', 1), 250, [
                'defaultSortDirection' => 'desc'
            ])
        ]);
    }
}
