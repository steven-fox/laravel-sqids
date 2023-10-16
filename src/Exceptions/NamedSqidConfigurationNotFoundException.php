<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;

class NamedSqidConfigurationNotFoundException extends Exception
{
    public static function make(string $name): static
    {
        return new static("The Sqid configuration named [{$name}] was not found.");
    }
}
