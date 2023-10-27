<?php

namespace StevenFox\LaravelSqids\Sqids;

use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Exceptions\DecodedSqidCannotBeCastToIntException;

class DecodedSqid
{
    public const CONFIG_NAME = null;

    public const ENCODED_SQID_CLASS = EncodedSqid::class;

    protected ConfigBasedSqidder $sqidder;

    public function __construct(
        protected array $numbers,
        ConfigBasedSqidder $sqidder = null,
    ) {
        $sqidder ??= app(ConfigBasedSqidder::class);
        $this->sqidder = $sqidder->forConfig($this->configName());
    }

    public static function new(int ...$numbers): static
    {
        return static::newFromArray($numbers);
    }

    /**
     * @param  int[]  $numbers
     */
    public static function newFromArray(array $numbers): static
    {
        return new static($numbers);
    }

    public function encode(): EncodedSqid
    {
        return $this->makeEncodedSqid(
            $this->sqidder->encode($this->numbers())
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

    protected function configName(): ?string
    {
        return static::CONFIG_NAME;
    }

    protected function makeEncodedSqid(string $id): EncodedSqid
    {
        return ($this->encodedSqidClass())::new($id);
    }

    /**
     * @return class-string<EncodedSqid>
     */
    protected function encodedSqidClass(): string
    {
        return static::ENCODED_SQID_CLASS;
    }
}
