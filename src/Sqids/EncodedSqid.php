<?php

namespace StevenFox\LaravelSqids\Sqids;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\DecodesSqids;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidDecodesToBlankException;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidIsNotCanonicalException;

class EncodedSqid implements DecodesSqids
{
    public function __construct(
        private string $id,
        private SqidsInterface $decoder,
        private SqidsManager $sqidsManager,
    ) {
    }

    public static function new(string $id, string $sqidConfigName = null): static
    {
        /** @var SqidsManager $sqidsManager */
        $sqidsManager = app(SqidsManager::class);
        $coder = blank($sqidConfigName)
            ? $sqidsManager->coderForDefaultConfig()
            : $sqidsManager->coderForSqidConfigName($sqidConfigName);

        return new static(
            $id,
            $coder,
            $sqidsManager,
        );
    }

    public function usingDefaultConfig(): static
    {
        return new static(
            $this->id,
            $this->sqidsManager->coderForDefaultConfig(),
            $this->sqidsManager,
        );
    }

    public function usingConfigName(string $configName): static
    {
        return new static(
            $this->id,
            $this->sqidsManager->coderForSqidConfigName($configName),
            $this->sqidsManager,
        );
    }

    public function usingConfig(SqidConfiguration $sqidConfiguration): static
    {
        return new static(
            $this->id,
            $this->sqidsManager->coderForSqidConfig($sqidConfiguration),
            $this->sqidsManager,
        );
    }

    public function decode(bool $validate = true): DecodedSqid
    {
        if ($validate) {
            $this->validate();
        }

        return new DecodedSqid(
            $this->decoder->decode($this->id()),
            $this->decoder,
            $this->sqidsManager,
        );
    }

    public function validate(): static
    {
        if ($this->isNotCanonical()) {
            throw EncodedSqidIsNotCanonicalException::make($this);
        }

        if ($this->decodesToBlank()) {
            throw EncodedSqidDecodesToBlankException::make($this);
        }

        return $this;
    }

    public function canonical(): static
    {
        return $this->decode(false)->encode();
    }

    public function isCanonical(): bool
    {
        return $this->id() === $this->canonical()->id();
    }

    public function isNotCanonical(): bool
    {
        return ! $this->isCanonical();
    }

    public function decodesToBlank(): bool
    {
        return empty($this->decode(false)->numbers());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id();
    }
}
