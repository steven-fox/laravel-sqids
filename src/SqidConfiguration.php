<?php

namespace StevenFox\LaravelSqids;

class SqidConfiguration
{
    public function __construct(
        public string $name,
        public string $alphabet,
        public int $minLength,
        public array $blocklist,
    ) {
    }
}
