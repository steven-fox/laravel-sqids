<?php

namespace StevenFox\LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StevenFox\LaravelSqids\LaravelSqids
 */
class LaravelSqids extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \StevenFox\LaravelSqids\LaravelSqids::class;
    }
}
