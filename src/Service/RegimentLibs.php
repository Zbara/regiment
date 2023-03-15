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

    const LEVEL = [
        0,
        1000,
        3000,
        6000,
        10000,
        15000,
        21000,
        28000,
        36000,
        45000,
        55000,
        66000,
        78000,
        91000,
        105000,
        120000,
        136000,
        153000,
        171000,
        190000,
        210000,
        231000,
        253000,
        276000,
        300000,
        325000,
        351000,
        378000,
        406000,
        435000,
        465000,
        496000,
        528000,
        561000,
        605000,
        640000,
        676000,
        713000,
        751000,
        790000,
        830000,
        871000,
        913000,
        956000,
        1000000,
        1045000,
        1091000,
        1138000,
        1186000,
        1235000,
        1285000,
        1336000,
        1388000,
        1441000,
        1495000,
        1550000,
        1606000,
        1663000,
        1721000,
        1780000,
        1840000,
        1901000,
        1963000,
        2026000,
        2090000,
        2155000,
        2221000,
        2288000,
        2356000,
        2425000,
        2495000,
        2566000,
        2638000,
        2711000,
        2785000,
        2860000,
        2936000,
        3013000,
        3091000,
        3170000,
        3250000,
        3331000,
        3413000,
        3496000,
        3580000,
        3665000,
        3751000,
        3838000,
        3926000,
        4015000,
        4105000,
        4196000,
        4288000,
        4381000,
        4475000,
        4570000,
        4666000,
        4763000,
        4861000,
        4960000,
        5060000,
        5161000,
        5263000,
        5366000,
        5470000,
        5575000,
        5681000,
        5788000,
        5897000,
        6007000,
        6118000,
        6230000,
        6343000,
        6457000,
        6572000,
        6688000,
        6805000,
        6923000,
        7042000,
        7162000,
        7283000,
        7405000,
        7528000,
        7652000,
        7777000,
        7903000,
        8030000,
        8158000,
        8287000,
        8417000,
        8548000,
        8680000,
        8813000,
        8947000,
        9082000,
        9218000,
        9355000,
        9493000,
        9632000,
        9772000,
        9913000,
        10055000,
        10198000,
        10342000,
        10487000,
        10633000,
        10780000,
        10928000,
        11077000,
        11227000,
        11378000,
        11530000,
        11683000,
        11837000,
        11992000,
        12148000,
        12305000,
        12463000,
        12622000,
        12782200,
        12943000,
        13105000,
        13268000,
        13432000,
        13597000,
        13763000,
        13930000,
        14098000,
        14267000,
        14437000,
        14608000,
        14780000,
        14953000,
        15127000,
        15302000,
        15478000,
        15655000,
        15833000,
        16012000,
        16192000,
        16373000,
        16555000,
        16738000,
        16922000,
        17107000,
        17293000,
        17480000,
        17668000,
        17857000,
        18047000,
        18238000,
        18430000,
        18623000,
        18817000,
        19012000,
        19208000,
        19405000,
        19603000,
        19802000,
        20002000,
        20203000,
        20405000,
        20608000,
        20812000,
        21017000,
        21223000,
        21430000,
        21638000,
        21847000,
        22057000,
        22268000,
        22480000,
        22693000,
        22907000,
        23122000,
        23338000,
        23555000,
        23773000,
        23992000,
        24212000,
        24433000,
        24655000,
        24878000,
        25102000,
        25327000,
        25553000,
        25780000,
        26008000,
        26237000,
        26467000,
        26698000,
        26930000,
        27163000,
        27397000,
        27632000,
        27868000,
        28105000,
        28343000,
        28582000,
        28822000,
        29063000,
        29305000,
        29548000,
        29792000,
        30037000,
        30283000,
        30530000,
        30778000,
        31027000,
        31277000,
        31528000,
        31780000,
        32033000,
        32287000,
        32542000,
        32798000,
        33055000,
        33313000,
        33572000,
        33832000,
        34093000,
        34355000,
        34618000,
        34882000,
        35147000,
        35413000,
        35680000,
        35948000,
        36217000,
        36487000,
        36758000,
        37030000,
        37303000,
        37577000,
        37852000,
        38128000,
        38405000,
        38683000,
        38962000,
        39242000,
        39523000,
        39805000,
        40088000,
        40372000,
        40657000,
        40943000,
        41230000,
        41518000,
        41807000,
        42097000,
        42388000,
        42680000,
        42973000,
        43267000,
        43562000,
        43858000,
        44155000,
        44453000,
        44752000,
        45052000,
        45353000,
        45655000,
        45958000,
        46262000,
        46567000,
        46873000,
        47180000,
        47488000,
        47797000,
        48107000,
        48418000,
        48730000,
        49043000,
        49357000,
        49672000,
        49988000,
        50305000,
        50623000,
        50942000,
        51262000,
        51583000,
        51905000,
        52228000,
        52552000,
        52877000,
        53203000,
        53530000,
        53858000,
        54187000,
        54517000,
        54848000,
        55180000,
        55513000,
        55847000,
        56182000,
        56518000,
        56855000,
        57193000,
        57532000,
        57872000,
        58213000,
        58555000,
        58898000,
        59242000,
        59587000,
        59933000,
        60280000,
        60628000,
        60977000,
        61327000,
        61678000,
        62030000,
        62383000,
        62737000,
        63092000,
        63448000,
        63805000,
        64163000,
        64522000,
        64882000,
        65243000,
        65605000,
        65968000,
        66332000,
        66697000,
        67063000,
        67430000,
        67798000,
        68167000,
        68537000,
        68908000,
        69280000,
        69653000,
        70027000,
        70402000,
        70778000,
        71155000,
        71533000,
        71912000,
        72292000,
        72673000,
        73055000,
        73438000,
        73822000,
        74207000,
        74593000,
        74980000,
        75368000,
        75757000,
        76147000,
        76538000,
        76930000,
        77323000,
        77717000,
        78112000,
        78508000,
        78905000,
        79303000,
        79702000,
        80102000,
        80503000,
        80905000,
        81308000,
        81712000,
        82117000,
        82523000,
        82930000,
        83338000,
        83747000,
        84157000,
        84568000,
        84980000,
        85393000,
        85807000,
        86222000,
        86638000,
        87055000,
        87473000,
        87892000,
        88312000,
        88733000,
        89155000,
        89578000,
        90002000,
        90427000,
        90853000,
        91280000,
        91708000,
        92137000,
        92567000,
        92998000,
        93430000,
        93863000,
        94297000,
        94732000,
        95168000,
        95605000,
        96043000,
        96482000,
        96922000,
        97363000,
        97805000,
        98248000,
        98692000,
        99137000,
        99583000,
        100030000,
        100478000,
        100927000,
        101377000,
        101828000,
        102280000,
        102733000,
        103187000,
        103642000,
        104098000,
        104555000,
        105013000,
        105472000,
        105932000,
        106393000,
        106855000,
        107318000,
        107782000,
        108247000,
        108713000,
        109180000,
        109648000,
        110117000,
        110587000,
        111058000,
        111530000,
        112003000,
        112477000,
        112952000,
        113428000,
        113905000,
        114383000,
        114862000,
        115342000,
        115823000,
        116305000,
        116788000,
        117272000,
        117757000,
        118243000,
        118730000,
        119218000,
        119707000,
        120197000,
        120688000,
        121180000,
        121673000,
        122167000,
        122662000,
        123158000,
        123655000,
        124153000,
        124652000,
        125152000,
        125653000,
        126155000,
        126658000,
        127162000,
        127667000,
        128173000,
        128680000,
        129188000,
        129697000,
        130207000,
        130718000,
        131230000,
        131743000,
        132257000,
        132772000,
        133288000,
        133805000,
        134323000,
        134842000,
        135362000,
        135883000,
        136405000,
        136928000,
        137452000,
        137977000,
        138503000,
        139030000,
        139558000,
        140087000,
        140617000,
        141148000,
        141680000,
        142213000,
        142747000,
        143282000,
        143818000,
        144355000,
        144893000,
        145432000,
        145972000,
        146513000,
        147055000,
        147598000,
        148142000,
        148687000,
        149233000,
        149780000,
        150328000,
        150877000,
        151427000
    ];

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
                'max' => 2000,
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
