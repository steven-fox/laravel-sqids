<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class EncodedSqidDecodesToBlankException extends Exception
{
    public EncodedSqid $sqid;

    public static function make(EncodedSqid $sqid): static
    {
        $static = new static("The id [{$sqid->id()}] does not decode to one or more numbers.");
        $static->sqid = $sqid;

        return $static;
    }
}
