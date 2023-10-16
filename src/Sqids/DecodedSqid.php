<?php

namespace StevenFox\LaravelSqids\Sqids;

use Illuminate\Support\Collection;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Exceptions\DecodedSqidCannotBeCastToIntException;
use StevenFox\LaravelSqids\SqidConfiguration;

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
        $sqidsManager = app(SqidsManager::class);

        return new static(
            $numbers,
            $sqidsManager->coderForSqidConfig($sqidConfigName),
            $sqidsManager,
        );
    }

    public function usingSqidConfigName(string $configName = null): static
    {
        return new static(
            $this->numbers,
            $this->sqidsManager->coderForSqidConfig($configName),
            $this->sqidsManager,
        );
    }

    public function usingSqidConfig(SqidConfiguration $sqidConfiguration = null): static
    {
        return new static(
            $this->numbers,
            $this->sqidsManager->coderForSqidConfig($sqidConfiguration?->name),
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

    public function dd()
    {
        dd([
            'numbers' => $this->numbers,
        ]);
    }

    public function __toString(): string
    {
        return Collection::make($this->numbers())->join(', ');
    }
}
