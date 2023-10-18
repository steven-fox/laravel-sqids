<?php

namespace StevenFox\LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;

/**
 * @see \StevenFox\LaravelSqids\Sqidder
 *
 * @method static string encode(array $numbers)
 * @method static array decode(string $id)
 * @method static SqidsInterface coderForDefaultConfig()
 * @method static SqidsInterface coderForSqidConfigName(string $name)
 * @method static SqidsInterface coderForSqidConfig(SqidConfiguration $config)
 */
class Sqidder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \StevenFox\LaravelSqids\Sqidder::class;
    }
}
