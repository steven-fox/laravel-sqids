<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Factories\CoderFactory;

beforeEach(function () {
    $this->factory = new CoderFactory(new ConfigRepository(config()));
});

it('implements the CoderFactory interface', function () {
    expect($this->factory)->toBeInstanceOf(\StevenFox\LaravelSqids\Contracts\CoderFactory::class);
});

it('can make a SqidsInterface instance for a SqidConfiguration', function () {
    $config = new SqidConfiguration(
        name: 'foo',
        alphabet: 'abc',
        minLength: 0,
        blocklist: [],
    );

    $sqidEncoder = $this->factory->forConfig($config);

    expect($sqidEncoder)
        ->toBeInstanceOf(SqidsInterface::class)
        ->and($sqidEncoder->encode([1]))->toBe('aa')
        ->and($sqidEncoder->decode('aa'))->toBe([1]);
});

it('can make a SqidsInterface instance for a config name', function () {
    $sqidEncoder = $this->factory->forConfigName('primary');

    expect($sqidEncoder)
        ->toBeInstanceOf(SqidsInterface::class)
        ->and($sqidEncoder->encode([1]))->toBe('Uk')
        ->and($sqidEncoder->decode('Uk'))->toBe([1]);
});

it('can make a SqidsInterface instance for the default config', function () {
    $sqidEncoder = $this->factory->forDefaultConfig();

    expect($sqidEncoder)
        ->toBeInstanceOf(SqidsInterface::class)
        ->and($sqidEncoder->encode([1]))->toBe('Uk')
        ->and($sqidEncoder->decode('Uk'))->toBe([1]);
});
