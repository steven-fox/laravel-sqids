<?php

namespace StevenFox\LaravelSqids\Contracts;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;

interface CoderFactory
{
    public function forConfig(SqidConfiguration $config): SqidsInterface;

    public function forDefaultConfig(): SqidsInterface;

    public function forConfigName(string $name): SqidsInterface;
}
