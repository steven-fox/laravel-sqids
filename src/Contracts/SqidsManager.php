<?php

namespace StevenFox\LaravelSqids\Contracts;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;

interface SqidsManager extends SqidsInterface
{
    public function coderForDefaultConfig(): SqidsInterface;

    public function coderForSqidConfigName(string $configName): SqidsInterface;

    public function coderForSqidConfig(SqidConfiguration $config): SqidsInterface;
}
