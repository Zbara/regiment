<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;

class Top
{

    private Security $security;
    private Vkontakte $vkontakte;

    public function __construct(Security $security, Vkontakte $vkontakte)
    {
        $this->security = $security;
        $this->vkontakte = $vkontakte;
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
            $friends = $this->vkontakte->getApi('https://api.vk.com/method/execute', [
                'access_token' => $this->security->getUser()->getAccessToken(),
                'v' => '5.161',
                'code' => $code
            ]);
            return $friends['response'] ?? [];
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
