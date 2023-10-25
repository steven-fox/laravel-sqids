<?php

namespace StevenFox\LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StevenFox\LaravelSqids\Sqidder
 *
 * @method static string encode(array $numbers)
 * @method static array decode(string $id)
 * @method static \StevenFox\LaravelSqids\Sqidder forConfig(string $name = null)
 */
class Sqidder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \StevenFox\LaravelSqids\Sqidder::class;
    }
}
