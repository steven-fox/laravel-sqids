<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;

class DefaultSqidConfigurationNotSetException extends Exception
{
    public static function make(): self
    {
        return new self('The default Sqid configuration is not set.');
    }
}
