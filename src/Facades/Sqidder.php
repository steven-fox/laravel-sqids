<?php

namespace StevenFox\LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StevenFox\LaravelSqids\Sqidder
 *
 * @method static string encode(array<int, int> $numbers)
 * @method static array<int, int> decode(string $id)
 * @method static \StevenFox\LaravelSqids\Sqidder forConfig(?string $name = null)
 */
class Sqidder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \StevenFox\LaravelSqids\Sqidder::class;
    }
}
