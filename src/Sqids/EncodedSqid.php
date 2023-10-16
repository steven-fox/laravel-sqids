<?php

namespace StevenFox\LaravelSqids\Sqids;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidDecodesToBlankException;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidIsNotCanonicalException;
use StevenFox\LaravelSqids\SqidConfiguration;

class EncodedSqid
{
    public function __construct(
        private string $id,
        private SqidsInterface $decoder,
        private SqidsManager $sqidsManager,
    ) {
    }

    public static function new(string $id, string $sqidConfigName = null): static
    {
        $sqidsManager = app(SqidsManager::class);

        return new static(
            $id,
            $sqidsManager->coderForSqidConfig($sqidConfigName),
            $sqidsManager,
        );
    }

    public function usingSqidConfigName(string $configName = null): static
    {
        return new static(
            $this->id,
            $this->sqidsManager->coderForSqidConfig($configName),
            $this->sqidsManager,
        );
    }

    public function usingSqidConfig(SqidConfiguration $sqidConfiguration = null): static
    {
        return new static(
            $this->id,
            $this->sqidsManager->coderForSqidConfig($sqidConfiguration?->name),
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

    public function validate(): self
    {
        if ($this->isNotCanonical()) {
            throw EncodedSqidIsNotCanonicalException::make($this);
        }

        if ($this->decodesToBlank()) {
            throw EncodedSqidDecodesToBlankException::make($this);
        }

        return $this;
    }

    public function canonical(): self
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

    public function toString(): string
    {
        return (string) $this;
    }

    public function dd(): never
    {
        dd([
            'id' => $this->id(),
        ]);
    }

    public function __toString(): string
    {
        return $this->id();
    }
}
