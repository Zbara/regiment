<?php

namespace App\Controller;

use App\Repository\UsersScriptRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin-users')]
    public function index(Request $request, PaginatorInterface $paginator, UsersScriptRepository $usersScriptRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'pagination' => $paginator->paginate($usersScriptRepository->findLatest(), $request->query->getInt('page', 1), 50, [
                'defaultSortDirection' => 'desc'
            ]),
            'lastTime' => count($usersScriptRepository->getLastId())
        ]);
    }
}
