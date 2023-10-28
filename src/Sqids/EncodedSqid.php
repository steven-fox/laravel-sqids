<?php

namespace StevenFox\LaravelSqids\Sqids;

use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidDecodesToBlankException;
use StevenFox\LaravelSqids\Exceptions\EncodedSqidIsNotCanonicalException;

class EncodedSqid
{
    protected ?string $configName = null;

    /** @var class-string<DecodedSqid> */
    protected string $decodedSqidClass = DecodedSqid::class;

    protected ConfigBasedSqidder $sqidder;

    final public function __construct(
        protected string $id,
        ConfigBasedSqidder $sqidder = null,
    ) {
        /** @var ?ConfigBasedSqidder $sqidder */
        $sqidder ??= app(ConfigBasedSqidder::class);
        $this->sqidder = $sqidder->forConfig($this->configName());
    }

    public static function new(string $id): static
    {
        return new static($id);
    }

    public function decodeOrFail(): DecodedSqid
    {
        $this->validate();

        return $this->decode();
    }

    public function decode(): DecodedSqid
    {
        return $this->makeDecodedSqid(
            $this->sqidder->decode($this->id())
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

    public function canonical(): EncodedSqid
    {
        return $this->decode()->encode();
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
        return empty(
            $this->decode()->numbers()
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id();
    }

    public function configName(): ?string
    {
        return $this->configName;
    }

    public function makeDecodedSqid(array $numbers): DecodedSqid
    {
        /** @var class-string<DecodedSqid> $decodedSqidClass */
        $decodedSqidClass = $this->decodedSqidClass();

        return $decodedSqidClass::newFromArray($numbers);
    }

    /**
     * @return class-string<DecodedSqid>
     */
    public function decodedSqidClass(): string
    {
        return $this->decodedSqidClass;
    }
}
