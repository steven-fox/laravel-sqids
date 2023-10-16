<?php

namespace StevenFox\LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;
use Sqids\SqidsInterface;

/**
 * @see \StevenFox\LaravelSqids\Sqidder
 *
 * @method static string encode(array $numbers)
 * @method static array decode(string $id)
 * @method static SqidsInterface coderForSqidConfig(string $id)
 */
class Sqidder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \StevenFox\LaravelSqids\Sqidder::class;
    }
}
