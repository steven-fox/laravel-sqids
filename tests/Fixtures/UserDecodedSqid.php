<?php

namespace StevenFox\LaravelSqids\Tests\Fixtures;

use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class UserDecodedSqid extends DecodedSqid
{
    public const CONFIG_NAME = 'user';

    protected function makeEncodedSqid(string $id): EncodedSqid
    {
        return UserEncodedSqid::new($id);
    }
}
