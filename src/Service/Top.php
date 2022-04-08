<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Top
{

    private Security $security;
    private Vkontakte $vkontakte;
    private Redis $redis;

    public function __construct(Security $security, Redis $redis, Vkontakte $vkontakte)
    {
        $this->security = $security;
        $this->vkontakte = $vkontakte;
        $this->redis = $redis;
    }

    public function getFriends()
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

        if ($this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            if (empty($this->redis->getValue('friends_' . $this->security->getUser()->getId(), 1))) {
                $friends = $this->vkontakte->getApi('https://api.vk.com/method/execute', [
                    'access_token' => $this->security->getUser()->getAccessToken(),
                    'v' => '5.161',
                    'code' => $code
                ]);

                if (isset($friends['response'])) {
                    $this->redis->setValue('friends_' . $this->security->getUser()->getId(), $friends['response'] ?? [], 300, 1);
                }
            }
            return $this->redis->getValue('friends_' . $this->security->getUser()->getId(), 1) ?? [];
        }
        return [];
    }

    public function getRequest()
    {
        if ($this->security->isGranted("IS_AUTHENTICATED_FULLY")) {
            $friends = $this->vkontakte->getApi('https://api.vk.com/method/friends.getRequests', [
                'access_token' => $this->security->getUser()->getAccessToken(),
                'v' => '5.161',
            ]);
            return $friends['response'] ?? [];
        }
        return [];
    }
}
