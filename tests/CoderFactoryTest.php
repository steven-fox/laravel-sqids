<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Factories\CoderFactory;

it('implements the CoderFactory interface', function () {
    $factory = new CoderFactory();

    expect($factory)->toBeInstanceOf(\StevenFox\LaravelSqids\Contracts\CoderFactory::class);
});

it('can make a SqidsInterface instance', function () {
    $factory = new CoderFactory();
    $config = new SqidConfiguration(
        name: 'foo',
        alphabet: 'abc',
        minLength: 0,
        blocklist: [],
    );

    $sqidEncoder = $factory->makeForSqidConfig($config);

    expect($sqidEncoder)
        ->toBeInstanceOf(SqidsInterface::class)
        ->and($sqidEncoder->encode([1]))->toBe('aa')
        ->and($sqidEncoder->decode('aa'))->toBe([1]);
});
