<?php

namespace StevenFox\LaravelSqids\Config;

class SqidConfiguration
{
    /**
     * @param  array<int, string>  $blocklist
     */
    public function __construct(
        public string $name,
        public string $alphabet,
        public int $minLength,
        public array $blocklist,
    ) {}

    /**
     * @return array{name: string, alphabet: string, minLength: int, blocklist: array<int, string>}
     */
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
