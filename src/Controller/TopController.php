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

        $code = 'var members = API.groups.getMembers({"group_id": 99584106, "v": "5.103", "sort": "id_asc", "count": "1000", "offset": 0, "fields": "photo_50"}).items;'
            . 'var offset = 1000;'
            . 'while (offset < 25000 && (offset + 0) < 9159)'
            . '{'
            . 'members = members + API.groups.getMembers({"group_id": 99584106, "v": "5.103", "sort": "id_asc", "count": "1000", "offset": (0 + offset), "fields": "photo_50"}).items;'
            . 'offset = offset + 1000;'
            . '};'
            . 'return members;';


        return $this->render('top/index.html.twig', [
            'pagination' => $paginator->paginate($regimentUsersRepository->findLatest(), $request->query->getInt('page', 1), 250, [
                'defaultSortDirection' => 'desc'
            ]),
            'update' => $regimentUsersRepository->updateTime()
        ]);
    }
}
