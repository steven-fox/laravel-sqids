<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class EncodedSqidIsNotCanonicalException extends Exception
{
    public EncodedSqid $sqid;

    public static function make(EncodedSqid $sqid): static
    {
        $static = new static("The id [{$sqid->id()}] is not canonical.");
        $static->sqid = $sqid;

        return $static;
    }
}
