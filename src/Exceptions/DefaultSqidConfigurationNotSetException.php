<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;

class DefaultSqidConfigurationNotSetException extends Exception
{
    public static function make(): static
    {
        return new static('The default Sqid configuration is not set.');
    }
}
