<?php

namespace StevenFox\LaravelSqids\Contracts;

use Sqids\SqidsInterface;

interface ConfigBasedSqidder extends SqidsInterface
{
    public function forConfig(string $name = null): static;
}
