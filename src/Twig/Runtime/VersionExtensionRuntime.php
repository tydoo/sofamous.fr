<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class VersionExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly string $version)
    {
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
