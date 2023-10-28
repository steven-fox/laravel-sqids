<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class EncodedSqidIsNotCanonicalException extends Exception
{
    public EncodedSqid $sqid;

    public static function make(EncodedSqid $sqid): self
    {
        $static = new self("The id [{$sqid->id()}] is not canonical.");
        $static->sqid = $sqid;

        return $static;
    }
}
