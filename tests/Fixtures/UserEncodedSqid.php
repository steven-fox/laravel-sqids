<?php

namespace StevenFox\LaravelSqids\Tests\Fixtures;

use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class UserEncodedSqid extends EncodedSqid
{
    public const CONFIG_NAME = UserDecodedSqid::CONFIG_NAME;

    protected function makeDecodedSqid(array $numbers): DecodedSqid
    {
        return UserDecodedSqid::newFromArray($numbers);
    }
}
