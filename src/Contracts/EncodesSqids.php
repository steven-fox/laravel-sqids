<?php

namespace StevenFox\LaravelSqids\Contracts;

interface EncodesSqids
{
    public function encode(): DecodesSqids;

    public function numbers(): array;

    public function toInt(): int;
}
