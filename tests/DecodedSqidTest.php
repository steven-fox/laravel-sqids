<?php

use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Exceptions\DecodedSqidCannotBeCastToIntException;
use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

it('can encode using the default configuration', function () {
    $sqid = DecodedSqid::new(1)
        ->usingDefaultConfig();

    $encoded = $sqid->encode();

    expect($encoded)
        ->toBeInstanceOf(EncodedSqid::class)
        ->and($encoded->id())->toEqual('Uk');
});

it('can be instantiated via the new method, without passing a config, to use the default config', function () {
    $sqid = DecodedSqid::new(1);

    expect($sqid)
        ->toBeInstanceOf(DecodedSqid::class)
        ->and($sqid->encode()->id())->toBe('Uk');
});

it('can be instantiated via the newFromArray method and a specified config name', function () {
    $sqid = DecodedSqid::newFromArray([1], 'other');

    expect($sqid)
        ->toBeInstanceOf(DecodedSqid::class)
        ->and($sqid->numbers())->toBe([1])
        ->and($sqid->encode()->id())->toBe('aa');
});

it('can encode using a specific configuration name after instantiation', function () {
    $sqid = DecodedSqid::new(1)
        ->usingConfigName('other');

    $encoded = $sqid->encode();

    expect($encoded)
        ->toBeInstanceOf(EncodedSqid::class)
        ->and($encoded->id())->toEqual('aa');
});

it('can encode using a specific configuration object after instantiation', function () {
    $sqid = DecodedSqid::new(1)
        ->usingConfig(new SqidConfiguration(
            'foo',
            'xyz',
            0,
            []
        ));

    $encoded = $sqid->encode();

    expect($encoded)
        ->toBeInstanceOf(EncodedSqid::class)
        ->and($encoded->id())->toEqual('yy');
});

it('can provide access to its internal numbers', function () {
    $sqid = DecodedSqid::new(1, 2, 3);
    expect($sqid->numbers())->toBe([1, 2, 3]);

    $sqid = DecodedSqid::newFromArray([4, 5, 6]);
    expect($sqid->numbers())->toBe([4, 5, 6]);
});

it('can be casted to an int when the internal numbers array is a single integer', function () {
    $sqid = DecodedSqid::new(10);

    expect($sqid->toInt())
        ->toBe(10);
});

it('will throw an exception when converting to an int for a sqid containing multiple integers', function () {
    $sqid = DecodedSqid::new(1, 2, 3);

    $exception = DecodedSqidCannotBeCastToIntException::containsMultipleNumbers($sqid);
    $this->expectException($exception::class);
    $this->expectExceptionMessage($exception->getMessage());

    $sqid->toInt();
});

it('will throw an exception when converting to an int for a sqid containing non integers', function () {
    $sqid = DecodedSqid::newFromArray([1.1]);

    $exception = DecodedSqidCannotBeCastToIntException::numberIsNotAnInt($sqid);
    $this->expectException($exception::class);
    $this->expectExceptionMessage($exception->getMessage());

    $sqid->toInt();
});

it('can be cast to a string by joining the numbers into a csv list', function () {
    $sqid = DecodedSqid::new(1, 2, 3);
    expect($sqid->toString())->toBe('1, 2, 3')
        ->and((string) $sqid)->toBe('1, 2, 3');

    $sqid = DecodedSqid::new(1);
    expect($sqid->toString())->toBe('1')
        ->and((string) $sqid)->toBe('1');
});
