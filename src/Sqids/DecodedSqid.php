<?php

namespace StevenFox\LaravelSqids\Sqids;

use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Exceptions\DecodedSqidCannotBeCastToIntException;

class DecodedSqid
{
    protected ?string $configName = null;

    /** @var class-string<EncodedSqid> */
    protected string $encodedSqidClass = EncodedSqid::class;

    protected ConfigBasedSqidder $sqidder;

    /**
     * @param  array<int, int>  $numbers
     */
    final public function __construct(
        protected array $numbers,
        ?ConfigBasedSqidder $sqidder = null,
    ) {
        $sqidder ??= app(ConfigBasedSqidder::class);
        $this->sqidder = $sqidder->forConfig($this->configName());
    }

    public static function new(int ...$numbers): static
    {
        return static::newFromArray($numbers);
    }

    /**
     * @param  array<int, int>  $numbers
     */
    public static function newFromArray(array $numbers, ?ConfigBasedSqidder $sqidder = null): static
    {
        return new static($numbers, $sqidder);
    }

    public function encode(): EncodedSqid
    {
        return $this->makeEncodedSqid(
            $this->sqidder->encode($this->numbers())
        );
    }

    /**
     * @return array<int, int>
     */
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

    public function configName(): ?string
    {
        return $this->configName;
    }

    public function makeEncodedSqid(string $id): EncodedSqid
    {
        /** @var class-string<EncodedSqid> $encodedSqidClass */
        $encodedSqidClass = $this->encodedSqidClass();

        return $encodedSqidClass::new($id, $this->sqidder);
    }

    /**
     * @return class-string<EncodedSqid>
     */
    public function encodedSqidClass(): string
    {
        return $this->encodedSqidClass;
    }
}
