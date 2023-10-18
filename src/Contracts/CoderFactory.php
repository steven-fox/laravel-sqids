<?php

namespace StevenFox\LaravelSqids\Contracts;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;

interface CoderFactory
{
    public function makeForSqidConfig(SqidConfiguration $config): SqidsInterface;
}
