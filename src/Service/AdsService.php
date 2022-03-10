<?php

namespace App\Service;

use App\Entity\Ads;
use App\Repository\AdsRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use function Symfony\Component\Translation\t;

class AdsService
{
    private Vkontakte $vkontakte;
    private DataResponse $dataResponse;
    private EntityManagerInterface $entityManager;

    const TYPE = [
        'group' => [
            'name' => 'Группа или паблик',
            'title' => 'Вступи',
            'url' => '//vk.com/public'
        ],
        'user' => [
            'name' => 'Личная страница',
            'title' => 'Добавь',
            'url' => '//vk.com/id'
        ],
        'host' => [
            'name' => 'Сервер',
            'title' => 'Хочешь друзей?',
            'url' => '//regiment.zbara.ru/ads?ref='
        ]
    ];
    private Environment $environment;
    private AdsRepository $adsRepository;
    private Security $security;

    public function __construct(
        Vkontakte              $vkontakte,
        DataResponse           $dataResponse,
        Environment             $environment,
        EntityManagerInterface $entityManager,
        AdsRepository $adsRepository,
        Security $security
    )
    {
        $this->entityManager = $entityManager;
        $this->vkontakte = $vkontakte;
        $this->environment = $environment;
        $this->dataResponse = $dataResponse;
        $this->adsRepository = $adsRepository;
        $this->security = $security;
    }

    public function create(Ads $ads): array
    {
        $adsUser = $this->adsRepository->findBy(['user' => $this->security->getUser()]);

        if(count($adsUser) <= 3) {
            if ($alias = $this->vkontakte->getUserId($ads->getRedirect(), $_ENV['ACCESS_TOKEN'])) {
                $ads->setRedirect($alias['object_id'])
                    ->setType($alias['type']);

                $this->entityManager->persist($ads);
                $this->entityManager->flush();

                return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                    'save' => $this->environment->render('ads/ads.item.html.twig', [
                        'ads' => $ads,
                        'type' => self::TYPE
                    ]),
                    'messages' => 'Аккаунт добавлен на пиар.'
                ]);
            }
            return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'Не верная ссылка, проверьте ссылку правильная ли она?');
        }
        return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'Допускается только 3 рекламы с одного аккаунта.');
    }

    public function remove(Ads $ads)
    {
        $this->entityManager->remove($ads);
        $this->entityManager->flush();

        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
            'messages' => 'Аккаунт удален!'
        ]);
    }

    public function user(): string
    {
        $ads = $this->adsRepository->getRandomEntities();

        $color = ['000000','009600','009600','009600','000096','000096','E69600','E69600','E69600','E69600','FF1111','FF1111','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000','FF0000'];

        if(isset($ads)){
            $ads->setViews($ads->getViews() + 1);
            $this->entityManager->flush();

            return  '<div style="border: solid 1px black; padding: 1px;">' . self::TYPE[$ads->getType()]['title'] . ': <a target="_blank" href="https://regiment.zbara.ru/ads/views/' . $ads->getId() . '">' . $ads->getName() . '</a>  —  <span style="color: #' . $color[array_rand($color, 1)] .'">' . $ads->getMessages() . '</span></div>';
        }
        return '';
    }
}
