<?php

namespace App\EventSubscriber;

use App\Entity\StatsLogsVisit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Security;

class StatsSubscriber implements EventSubscriberInterface
{

    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security){
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function onKernelResponse(RequestEvent $event)
    {
        $stats = new StatsLogsVisit();
        $stats->setIp($event->getRequest()->getClientIp())
            ->setPage($event->getRequest()->getRequestUri())
            ->setTime(time())
            ->setUa($event->getRequest()->headers->get('User-Agent'))
            ->setReferar($event->getRequest()->headers->get('Referer') ?? 'no');

        if($event->getRequest()->attributes->get('_route') == 'friends-social'){
            $stats->setPlatformId($event->getRequest()->request->get('ownerId', 0));
        }
        $this->entityManager->persist($stats);
        $this->entityManager->flush();
    }

    #[ArrayShape(['kernel.request' => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelResponse',
        ];
    }
}
