<?php

namespace App\Service;

use JetBrains\PhpStorm\ArrayShape;

class Clan
{
    #[ArrayShape(['category' => "string", 'name' => "string", 'url' => "string", 'resource' => "array"])]
    public function info(int $socId): ?array
    {
        if($socId === 135057576) {
            return [
                'category' => 'Рота',
                'name' => 'Zbara Dev',
                'url' => 'https://vk.com/club77525280',
                'resource' => [
                    'create' => time(),
                    'update' => time(),
                    'admin' => [135057576, 125449266],
                    'users' => 125
                ]
            ];
        } else return null;
    }
}
