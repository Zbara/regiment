<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Form\AdsType;
use App\Repository\AdsRepository;
use App\Service\AdsService;
use App\Service\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdsController extends AbstractController
{
    #[Route('/ads', name: 'ads')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, FormError $formError, AdsService $adsService, AdsRepository $adsRepository): Response
    {
        $ads = new Ads();
        $ads->setUser($this->getUser());

        $form = $this->createForm(AdsType::class, $ads);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->json($adsService->create($ads));
            }
            return $this->json(['status' => 0, 'error' => ['messages' => $formError->getErrorMessages($form)]]);
        }

        return $this->render('ads/index.html.twig', [
            'form' => $form->createView(),
            'items' => $adsRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC']),
            'type' => AdsService::TYPE
        ]);
    }

    #[
        Route('/ads/remove', name: 'ads-remove', methods: ['POST'])
    ]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function remove(Request $request, AdsRepository $adsRepository, AdsService $adsService): Response
    {
        if ($ads = $adsRepository->findOneBy(['id' => (int)$request->get('id', 0), 'user' => $this->getUser()])) {
            return $this->json($adsService->remove($ads));
        }
        return $this->json(['status' => 0, 'error' => ['messages' => 'Аккаунт не найден!']]);
    }

    #[
        Route('/ads/views/{id}', name: 'ads-views', methods: ['GET'])
    ]
    public function views(Request $request, AdsRepository $adsRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        if ($ads = $adsRepository->findOneBy(['id' => (int)$id])) {
            $ads->setLastTime(time())
                ->setCount($ads->getCount() + 1);

            $entityManager->flush();

            return $this->redirect(AdsService::TYPE[$ads->getType()]['url'] . $ads->getRedirect());
        }
        return $this->redirectToRoute('top');
    }
}
