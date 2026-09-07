<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\VersionExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class VersionExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_version', [VersionExtensionRuntime::class, 'getVersion']),
        ];
    }
}
