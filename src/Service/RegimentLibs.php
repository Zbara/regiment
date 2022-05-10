<?php

namespace App\Service;

use JetBrains\PhpStorm\ArrayShape;

abstract class RegimentLibs
{
    const ACHIEVEMENTS = [
        "weapons" => [0, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000],
        "bosses" => [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
        "boxes" => [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
        "missions" => [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500],
        "sut" => [0, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000],
        "exchange_collections" => [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500],
        "send_help" => [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
        "coins" => [0, 5000, 10000, 25000, 50000, 100000, 250000, 500000, 1000000, 2500000, 5000000],
        "tokens" => [0, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000, 250000, 500000],
        "encryptions" => [0, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000, 100000, 250000, 500000],
        "tickets" => [0, 5, 10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000],
        "damage" => [100000, 250000, 500000, 1000000, 3000000, 5000000, 10000000, 20000000, 30000000, 50000000, 75000000, 100000000, 250000000, 500000000, 1000000000, 5000000000, 10000000000, 25000000000, 50000000000, 100000000000, 25000000000, 50000000000, 100000000000 ],
        "open_package" => [1, 5, 10, 25, 50, 100, 200, 300, 500, 750, 1000, 2000]
    ];
    const LEVEL = [0, 1e3, 3e3, 6e3, 1e4, 15e3, 21e3, 28e3, 36e3, 45e3, 55e3, 66e3, 78e3, 91e3, 105e3, 12e4, 136e3, 153e3, 171e3, 19e4, 21e4, 231e3, 253e3, 276e3, 3e5, 325e3, 351e3, 378e3, 406e3, 435e3, 465e3, 496e3, 528e3, 561e3, 605e3, 64e4, 676e3, 713e3, 751e3, 79e4, 83e4, 871e3, 913e3, 956e3, 1e6, 1045e3, 1091e3, 1138e3, 1186e3, 1235e3, 1285e3, 1336e3, 1388e3, 1441e3, 1495e3, 155e4, 1606e3, 1663e3, 1721e3, 178e4, 184e4, 1901e3, 1963e3, 2026e3, 209e4, 2155e3, 2221e3, 2288e3, 2356e3, 2425e3, 2495e3, 2566e3, 2638e3, 2711e3, 2785e3, 286e4, 2936e3, 3013e3, 3091e3, 317e4, 325e4, 3331e3, 3413e3, 3496e3, 358e4, 3665e3, 3751e3, 3838e3, 3926e3, 4015e3, 4105e3, 4196e3, 4288e3, 4381e3, 4475e3, 457e4, 4666e3, 4763e3, 4861e3, 496e4, 506e4, 5161e3, 5263e3, 5366e3, 547e4, 5575e3, 5681e3, 5788e3, 5897e3, 6007e3, 6118e3, 623e4, 6343e3, 6457e3, 6572e3, 6688e3, 6805e3, 6923e3, 7042e3, 7162e3, 7283e3, 7405e3, 7528e3, 7652e3, 7777e3, 7903e3, 803e4, 8158e3, 8287e3, 8417e3, 8548e3, 868e4, 8813e3, 8947e3, 9082e3, 9218e3, 9355e3, 9493e3, 9632e3, 9772e3, 9913e3, 10055e3, 10198e3, 10342e3, 10487e3, 10633e3, 1078e4, 10928e3, 11077e3, 11227e3, 11378e3, 1153e4, 11683e3, 11837e3, 11992e3, 12148e3, 12305e3, 12463e3, 12622e3, 12782200, 12943e3, 13105e3, 13268e3, 13432e3, 13597e3, 13763e3, 1393e4, 14098e3, 14267e3, 14437e3, 14608e3, 1478e4, 14953e3, 15127e3, 15302e3, 15478e3, 15655e3, 15833e3, 16012e3, 16192e3, 16373e3, 16555e3, 16738e3, 16922e3, 17107e3, 17293e3, 1748e4, 17668e3, 17857e3, 18047e3, 18238e3, 1843e4, 18623e3, 18817e3, 19012e3, 19208e3, 19405e3, 19603e3, 19802e3, 20002e3, 20203e3, 20405e3, 20608e3, 20812e3, 21017e3, 21223e3, 2143e4, 21638e3, 21847e3, 22057e3, 22268e3, 2248e4, 22693e3, 22907e3, 23122e3, 23338e3, 23555e3, 23773e3, 23992e3, 24212e3, 24433e3, 24655e3, 24878e3, 25102e3, 25327e3, 25553e3, 2578e4, 26008e3, 26237e3, 26467e3, 26698e3, 2693e4, 27163e3, 27397e3, 27632e3, 27868e3, 28105e3, 28343e3, 28582e3, 28822e3, 29063e3, 29305e3, 29548e3, 29792e3, 30037e3, 30283e3, 3053e4, 30778e3, 31027e3, 31277e3, 31528e3, 3178e4, 32033e3, 32287e3, 32542e3 ];

    const ACHIEVEMENTS_LIST = [
        "win_boss_0" => ["title" => "Йенеке", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 0],
        "win_boss_1" => ["title" => "Рундштедт", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 1],
        "win_boss_2" => ["title" => "Манштейн", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 2],
        "win_boss_3" => ["title" => "Альмендингер", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 3],
        "win_boss_4" => ["title" => "Клейст", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 4],
        "win_boss_5" => ["title" => "Шёрнер", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 5],
        "win_boss_6" => ["title" => "Хубе", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 6],
        "win_boss_7" => ["title" => "Вёлер", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 7],
        "win_boss_8" => ["title" => "Бок", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 8],
        "win_boss_9" => ["title" => "Гот", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 9],
        "win_boss_10" => ["title" => "Паулюс", "type" => "boss", "category" => "bosses", "group" => "usual", "id" => 10],
        "win_boss_14" => ["title" => "Геббельс", "type" => "boss", "category" => "bosses", "group" => "night", "id" => 14],
        "win_boss_15" => ["title" => "Муссолини", "type" => "boss", "category" => "bosses", "group" => "night", "id" => 15],
        "win_boss_18" => ["title" => "Роммель", "type" => "boss", "category" => "bosses", "group" => "night", "id" => 18],
        "tokens" => ["title" => "Заработать жетоны", "type" => "resourse", "category" => "tokens"],
        "encryptions" => ["title" => "Заработать шифровки", "type" => "resourse", "category" => "encryptions"],
        "coins" => ["title" => "Заработать монеты", "type" => "resourse", "category" => "coins"],
        "tickets" => ["title" => "Раздобыть талоны", "type" => "resourse", "category" => "tickets"],
        "sut" => ["title" => "Поднять уровень техники", "type" => "other", "category" => "sut"],
        "total_damage" => ["title" => "Общий урон", "type" => "other", "category" => "damage"],
        "open_package" => ["title" => "Открыть забытую посылку", "type" => "other", "category" => "open_package"],
        "send_airstrike" => ["title" => "Отправить артобстрел", "type" => "other", "category" => "send_help"],
        "send_ammunition" => ["title" => "Отправить боезапас", "type" => "other", "category" => "send_help"],
        "exchange_collections" => ["title" => "Обменять набор коллекций", "type" => "other", "category" => "exchange_collections"],
        "weapon_0" => ["title" => "Трассирующие снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_1" => ["title" => "Осколочные снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_2" => ["title" => "Разрывные снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_3" => ["title" => "Зажигательные снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_4" => ["title" => "Фугасные снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_5" => ["title" => "Бронебойные снаряды", "type" => "weapon", "category" => "weapons"],
        "weapon_6" => ["title" => "Кумулятивные снаряды", "type" => "weapon", "category" => "weapons"],
        "open_box_0" => ["title" => "Открыть Простой ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_2" => ["title" => "Открыть Лёгкий ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_3" => ["title" => "Открыть Средний ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_4" => ["title" => "Открыть Большой ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_5" => ["title" => "Открыть Легендарный ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_6" => ["title" => "Открыть Фронтовой ящик", "type" => "boxes", "category" => "boxes"],
        "open_box_7" => ["title" => "Открыть ящик с танками", "type" => "boxes", "category" => "boxes"],
        "open_box_8" => ["title" => "Открыть ящик с артиллерией", "type" => "boxes", "category" => "boxes"],
        "open_box_9" => ["title" => "Открыть ящик с авиацией", "type" => "boxes", "category" => "boxes"],
        "open_box_11" => ["title" => "Открыть Лёгкий ящик с техникой", "type" => "boxes", "category" => "boxes"],
        "open_box_12" => ["title" => "Открыть Средний ящик с техникой", "type" => "boxes", "category" => "boxes"],
        "open_box_13" => ["title" => "Открыть Большой ящик с техникой", "type" => "boxes", "category" => "boxes"],
        "open_box_14" => ["title" => "Открыть Легендарный ящик с техникой", "type" => "boxes", "category" => "boxes"],
        "mission_0_0" => ["title" => "Пройти миссию Отступать некуда", "type" => "mission", "category" => "missions"],
        "mission_0_1" => ["title" => "Пройти миссию Засада в туннеле", "type" => "mission", "category" => "missions"],
        "mission_0_2" => ["title" => "Пройти миссию Один путь", "type" => "mission", "category" => "missions"],
        "mission_0_3" => ["title" => "Пройти миссию Скрытая угроза", "type" => "mission", "category" => "missions"],
        "mission_0_4" => ["title" => "Пройти миссию Город тишины", "type" => "mission", "category" => "missions"],
        "mission_0_5" => ["title" => "Пройти миссию Железная дорога", "type" => "mission", "category" => "missions"],
        "mission_0_6" => ["title" => "Пройти миссию Поезд спасения", "type" => "mission", "category" => "missions"],
        "mission_1_0" => ["title" => "Пройти миссию Атака из леса", "type" => "mission", "category" => "missions"],
        "mission_1_1" => ["title" => "Пройти миссию Место отгрузки", "type" => "mission", "category" => "missions"],
        "mission_1_2" => ["title" => "Пройти миссию Таинственная река", "type" => "mission", "category" => "missions"],
        "mission_1_3" => ["title" => "Пройти миссию Точка доступа", "type" => "mission", "category" => "missions"],
        "mission_1_4" => ["title" => "Пройти миссию Сквозь снег", "type" => "mission", "category" => "missions"],
        "mission_1_5" => ["title" => "Пройти миссию Закрытая зона", "type" => "mission", "category" => "missions"],
        "mission_1_6" => ["title" => "Пройти миссию Перекрёсток", "type" => "mission", "category" => "missions"],
        "mission_2_0" => ["title" => "Пройти миссию Затопленный груз", "type" => "mission", "category" => "missions"],
        "mission_2_1" => ["title" => "Пройти миссию Пролив", "type" => "mission", "category" => "missions"],
        "mission_2_2" => ["title" => "Пройти миссию Залечь на дно", "type" => "mission", "category" => "missions"],
        "mission_2_3" => ["title" => "Пройти миссию Два берега", "type" => "mission", "category" => "missions"],
        "mission_2_4" => ["title" => "Пройти миссию Доставка из штаба", "type" => "mission", "category" => "missions"],
        "mission_2_5" => ["title" => "Пройти миссию Площадь возмездия", "type" => "mission", "category" => "missions"],
        "mission_2_6" => ["title" => "Пройти миссию Портовый город", "type" => "mission", "category" => "missions"],
        "mission_3_0" => ["title" => "Пройти миссию Захват", "type" => "mission", "category" => "missions"],
        "mission_3_1" => ["title" => "Пройти миссию Шпионский мост", "type" => "mission", "category" => "missions"],
        "mission_3_2" => ["title" => "Пройти миссию В тылу врага", "type" => "mission", "category" => "missions"],
        "mission_3_3" => ["title" => "Пройти миссию Занять высоту", "type" => "mission", "category" => "missions"],
        "mission_3_4" => ["title" => "Пройти миссию Надзорная вышка", "type" => "mission", "category" => "missions"],
        "mission_3_5" => ["title" => "Пройти миссию Укрепление", "type" => "mission", "category" => "missions"],
        "mission_3_6" => ["title" => "Пройти миссию Штурм крепости", "type" => "mission", "category" => "missions"],
    ];

    const ACHIEVEMENTS_CATEGORY = [
        'weapon' => 'Боевые достижение',
        'other' => 'Другие',
        'resourse' => 'Валютные достижение',
        'mission' => 'Прохождение миссий',
        'boxes' => 'Открытие ящиков'
    ];

    const COLOR = [
        'damage' => ['000000', '009600', '009600', '009600', '000096', '000096', 'E69600', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF1111', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000'],
        'weapons' => ['000000', '000000', '000000', '000000', '000000', '000096', '000096', '000096', 'E69600', 'E69600', 'E69600', 'FF1111', 'FF0000', 'FF0000'],
        'sut' => ['000000', '0000C5', '0000C5', '0000C5', 'E69600', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000', 'FF0000'],
        'talant' => [
            [
                'min' => 0,
                'max' => 50,
                'color' => '#000000'
            ],
            [
                'min' => 51,
                'max' => 150,
                'color' => '#009600'
            ],
            [
                'min' => 151,
                'max' => 250,
                'color' => '#E69600'
            ],
            [
                'min' => 251,
                'max' => 1000,
                'color' => '#FF0000'
            ]
        ]
    ];

    const IMAGES = [
        'encryptions' => 'https://regiment.zbara.ru/images/encryptions.png',
        'tickets' => 'https://regiment.zbara.ru/images/tickets.png',
        'coins' => 'https://regiment.zbara.ru/images/coin.png',
    ];

    #[ArrayShape([
        'achievements' => "\int[][]",
        'achievements_list' => "\string[][]",
        'achievements_category' => "string[]",
        'level' => "int[]",
        'color' => "array",
        'images' => "string[]"]
    )]
    public static function libs(): array
    {
        return [
            'achievements' => self::ACHIEVEMENTS,
            'achievements_list' => self::ACHIEVEMENTS_LIST,
            'achievements_category' => self::ACHIEVEMENTS_CATEGORY,
            'level' => self::LEVEL,
            'color' => self::COLOR,
            'images' => self::IMAGES
        ];
    }
}
