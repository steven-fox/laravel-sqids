<?php

namespace StevenFox\LaravelSqids\Contracts;

interface DecodesSqids
{
    public function decode(bool $validate = true): EncodesSqids;

    public function validate(): DecodesSqids;

    public function canonical(): DecodesSqids;

    public function isCanonical(): bool;

    public function isNotCanonical(): bool;

    public function decodesToBlank(): bool;

    public function id(): string;

    public function __toString(): string;
}
