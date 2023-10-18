<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Factories\CoderFactory;
use StevenFox\LaravelSqids\Sqidder;

/**
 * See the Pest::beforeEach hook for config setup.
 */
beforeEach(function () {
    $this->sqidder = new Sqidder(
        new ConfigRepository(config()),
        new CoderFactory(),
    );
});

it('implements the SqidsInterface interface', function () {
    expect($this->sqidder)->toBeInstanceOf(SqidsInterface::class);
});

it('implements the SqidsManager interface', function () {
    expect($this->sqidder)->toBeInstanceOf(class: SqidsManager::class);
});

it('can encode an array of numbers using the default config', function () {
    expect($this->sqidder->encode([1]))->toBe('Uk')
        ->and($this->sqidder->encode([1, 2, 3]))->toBe('86Rf07');
});

it('can decode a sqid using the default config', function () {
    expect($this->sqidder->decode('Uk'))->toBe([1])
        ->and($this->sqidder->decode('86Rf07'))->toBe([1, 2, 3]);
});

it('can provide a coder for the default config', function () {
    $coder = $this->sqidder->coderForDefaultConfig();

    expect($coder)->toBeInstanceOf(SqidsInterface::class)
        ->and($coder->encode([1]))->toBe('Uk')
        ->and($coder->decode('Uk'))->toBe([1]);
});

it('can provide a coder for a specific config name', function () {
    $coder = $this->sqidder->coderForSqidConfigName('other');

    expect($coder)->toBeInstanceOf(SqidsInterface::class)
        ->and($coder->encode([1]))->toBe('aa')
        ->and($coder->decode('aa'))->toBe([1]);
});

it('can provide a coder for a specific config object', function () {
    $coder = $this->sqidder->coderForSqidConfig(new SqidConfiguration(
        'foo',
        'xyz',
        0,
        [],
    ));

    expect($coder)->toBeInstanceOf(SqidsInterface::class)
        ->and($coder->encode([1]))->toBe('yy')
        ->and($coder->decode('yy'))->toBe([1]);
});

it('has a facade', function () {
    expect(\StevenFox\LaravelSqids\Facades\Sqidder::getFacadeRoot())
        ->toBeInstanceOf(Sqidder::class);
});
