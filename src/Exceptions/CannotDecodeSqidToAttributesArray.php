<?php

namespace StevenFox\LaravelSqids\Exceptions;

use Exception;

class CannotDecodeSqidToAttributesArray extends Exception
{
    public static function numbersCountDoesNotMatchExpected(string $sqid): self
    {
        return new self(
            "The count of numbers from decoding the sqid [{$sqid}] does not match the expected amount."
        );
    }
}
