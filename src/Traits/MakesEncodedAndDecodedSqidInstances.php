<?php

namespace StevenFox\LaravelSqids\Traits;

use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

trait MakesEncodedAndDecodedSqidInstances
{
    protected function makeEncodedSqid(string $sqid): EncodedSqid
    {
        /** @var class-string<EncodedSqid> $class */
        $class = $this->encodedSqidClassName();

        return $class::new($sqid);
    }

    /**
     * @return class-string<EncodedSqid>
     */
    protected function encodedSqidClassName(): string
    {
        return EncodedSqid::class;
    }

    protected function makeDecodedSqid(array $numbers): DecodedSqid
    {
        /** @var class-string<DecodedSqid> $class */
        $class = $this->decodedSqidClassName();

        return $class::newFromArray($numbers);
    }

    /**
     * @return class-string<DecodedSqid>
     */
    protected function decodedSqidClassName(): string
    {
        return DecodedSqid::class;
    }
}
