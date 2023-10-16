<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;
use StevenFox\LaravelSqids\Sqids\DecodedSqid;

class DecodedSqidCannotBeCastToIntException extends Exception
{
    public DecodedSqid $sqid;

    public static function containsMultipleNumbers(DecodedSqid $sqid): static
    {
        $static = new static("The id cannot be converted to an int: it's defined with multiple numbers.");
        $static->sqid = $sqid;

        return $static;
    }

    public static function numberIsNotAnInt(DecodedSqid $sqid): static
    {
        $static = new static('The id cannot be converted to an int: it contains a non-integer number.');
        $static->sqid = $sqid;

        return $static;
    }
}
