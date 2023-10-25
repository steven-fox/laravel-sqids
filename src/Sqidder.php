<?php

namespace StevenFox\LaravelSqids;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;

class Sqidder implements ConfigBasedSqidder
{
    public function __construct(
        private Contracts\CoderFactory $coderFactory,
        private ?string $sqidConfigName = null,
    ) {
    }

    public function forConfig(string $name = null): static
    {
        $this->sqidConfigName = $name;

        return $this;
    }

    public function encode(array $numbers): string
    {
        return $this->coder()->encode($numbers);
    }

    public function decode(string $id): array
    {
        return $this->coder()->decode($id);
    }

    private function coder(): SqidsInterface
    {
        return $this->sqidConfigName
            ? $this->coderFactory->forConfigName($this->sqidConfigName)
            : $this->coderFactory->forDefaultConfig();
    }
}
