<?php

namespace StevenFox\LaravelSqids;

use Sqids\Sqids;
use Sqids\SqidsInterface;

class CoderFactory
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
