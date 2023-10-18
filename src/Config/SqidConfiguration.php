<?php

namespace StevenFox\LaravelSqids\Config;

class SqidConfiguration
{
    public function __construct(
        public string $name,
        public string $alphabet,
        public int $minLength,
        public array $blocklist,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'alphabet' => $this->alphabet,
            'minLength' => $this->minLength,
            'blocklist' => $this->blocklist,
        ];
    }
}
