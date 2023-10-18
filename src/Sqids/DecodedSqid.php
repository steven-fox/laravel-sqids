<?php

namespace StevenFox\LaravelSqids\Sqids;

use Illuminate\Support\Collection;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Exceptions\DecodedSqidCannotBeCastToIntException;

class DecodedSqid
{
    public function __construct(
        private array $numbers,
        private SqidsInterface $encoder,
        private SqidsManager $sqidsManager,
    ) {
    }

    public static function new(int ...$numbers): static
    {
        return static::newFromArray($numbers);
    }

    /**
     * @param  int[]  $numbers
     */
    public static function newFromArray(array $numbers, string $sqidConfigName = null): static
    {
        /** @var SqidsManager $sqidsManager */
        $sqidsManager = app(SqidsManager::class);
        $coder = blank($sqidConfigName)
            ? $sqidsManager->coderForDefaultConfig()
            : $sqidsManager->coderForSqidConfigName($sqidConfigName);

        return new static(
            $numbers,
            $coder,
            $sqidsManager,
        );
    }

    public function usingDefaultConfig(): static
    {
        return new static(
            $this->numbers,
            $this->sqidsManager->coderForDefaultConfig(),
            $this->sqidsManager,
        );
    }

    public function usingConfigName(string $configName): static
    {
        return new static(
            $this->numbers,
            $this->sqidsManager->coderForSqidConfigName($configName),
            $this->sqidsManager,
        );
    }

    public function usingConfig(SqidConfiguration $sqidConfiguration): static
    {
        return new static(
            $this->numbers,
            $this->sqidsManager->coderForSqidConfig($sqidConfiguration),
            $this->sqidsManager,
        );
    }

    public function encode(): EncodedSqid
    {
        return new EncodedSqid(
            $this->encoder->encode($this->numbers),
            $this->encoder,
            $this->sqidsManager,
        );
    }

    public function numbers(): array
    {
        return $this->numbers;
    }

    public function toInt(): int
    {
        if (count($this->numbers()) !== 1) {
            throw DecodedSqidCannotBeCastToIntException::containsMultipleNumbers($this);
        }

        if (! is_int($number = head($this->numbers()))) {
            throw DecodedSqidCannotBeCastToIntException::numberIsNotAnInt($this);
        }

        return $number;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return Collection::make($this->numbers())->join(', ');
    }
}
