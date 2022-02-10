<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppVersionExtension extends AbstractExtension
{
    public const MAJOR = 1;

    public function getFunctions(): array
    {
        return [
            new TwigFunction('gitDate', [$this, 'getDate']),
            new TwigFunction('gitHash', [$this, 'getHash']),
            new TwigFunction('gitFull', [$this, 'getFull']),
        ];
    }

    public static function getFull(): string
    {
        $branch = trim(exec('git symbolic-ref HEAD | sed -e "s/^refs\/heads\///"'));
        $rev = mb_str_split(exec('git rev-list HEAD --count'));

        return sprintf('build v%s.%s.%s-%s.%s', self::MAJOR, $rev[0], $rev[1], $branch, self::getHash());
    }

    public static function getDate(): string
    {
        return trim(exec("git log -1 --pretty=format:'%ct'"));
    }

    public static function getHash(): string
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        return sprintf('%s', $commitHash);
    }
}
