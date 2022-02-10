<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('rename', [$this, 'rename']),
            new TwigFunction('json_decode', [$this, 'jsonDecode']),
            new TwigFunction('convertTime', [$this, 'convertTime']),
        ];
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function rename(array $endings, $number): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        $n = $number;

        return sprintf($endings[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]], $n);
    }

    public function convertTime($time): string
    {
        $ts = (int) (time() - (int) $time);

        if (date('Y-m-d', $time) === date('Y-m-d', time())) {
            if ($ts < 1) {
                $date = 'только что';
            } elseif ($ts < 60) {
                $date = $this->rename(['%d секунду назад', '%d секунды назад', '%d секунд назад'], $ts);
            } elseif (60 === $ts) {
                $date = 'минуту назад';
            } elseif ($ts < 3600) {
                $date = $this->rename(['%d минуту назад', '%d минуты назад', '%d минут назад'], floor($ts / 60));
            } elseif (3600 === $ts) {
                $date = 'час назад';
            } else {
                $date = $this->rename(['%d час назад', '%d часа назад', '%d часов назад'], floor($ts / 3600));
            }
        } elseif (date('Y-m-d', $time) === date('Y-m-d', (time() - 84600))) {
            $date = date('вчера в H:i', $time);
        } else {
            $date = date('d.m.Y H:i', $time);
        }

        return $date;
    }
}
