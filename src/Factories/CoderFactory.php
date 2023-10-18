<?php

namespace StevenFox\LaravelSqids\Factories;

use Sqids\Sqids;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\CoderFactory as CoderFactoryInterface;

class CoderFactory implements CoderFactoryInterface
{
    public function __construct()
    {
    }

    public function makeForSqidConfig(SqidConfiguration $config): SqidsInterface
    {
        return new Sqids(
            alphabet: $config->alphabet,
            minLength: $config->minLength,
            blocklist: $config->blocklist,
        );
    }
}
