<?php

use StevenFox\LaravelSqids\Exceptions\EncodedSqidIsNotCanonicalException;
use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

it('can decode using the default configuration', function () {
    $sqid = new EncodedSqid('Uk');

    expect($sqid->configName())->toBeNull();

    $decoded = $sqid->decode();

    expect($decoded)
        ->toBeInstanceOf(DecodedSqid::class)
        ->and($decoded->numbers())->toEqual([1]);
});

it('can be instantiated via the new method to use the default config', function () {
    $sqid = EncodedSqid::new('Uk');

    expect($sqid)
        ->toBeInstanceOf(EncodedSqid::class)
        ->and($sqid->decode()->numbers())->toBe([1]);
});

it('can decode without validation', function () {
    $invalidSqid = EncodedSqid::new('a');

    $decoded = $invalidSqid->decode(false);

    expect($decoded->numbers())->toBe([]);
});

it('can provide access to its internal id', function () {
    $sqid = EncodedSqid::new('Ko');

    expect($sqid->id())->toBe('Ko');
});

it('can be casted to a string', function () {
    $sqid = EncodedSqid::new('Ko');

    expect((string) $sqid)
        ->toBe('Ko');
});

it('can validate its id for non canonical values', function () {
    $sqid = EncodedSqid::new('aa');

    $this->expectException(EncodedSqidIsNotCanonicalException::class);

    $sqid->validate();
});

it('can resolve its canonical id', function () {
    $sqid = EncodedSqid::new('AAA');

    expect($sqid->canonical()->id())->toBe('bbb');
});

it('can determine if its id is canonical', function () {
    $sqid = EncodedSqid::new('AAA');

    expect($sqid->isCanonical())->toBeFalse()
        ->and($sqid->isNotCanonical())->toBeTrue();

    $sqid = EncodedSqid::new('Uk');

    expect($sqid->isCanonical())->toBeTrue()
        ->and($sqid->isNotCanonical())->toBeFalse();
});

it('can determine if it decodes to blank', function () {
    $sqid = EncodedSqid::new('a');

    expect($sqid->decode(false)->numbers())->toBeEmpty()
        ->and($sqid->decodesToBlank())->toBeTrue();
});
