<?php

namespace StevenFox\LaravelSqids\Contracts;

use Sqids\SqidsInterface;

interface SqidsManager extends SqidsInterface
{
    public function coderForSqidConfig(string $configName = null): SqidsInterface;
}
