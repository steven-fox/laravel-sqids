<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\CoderFactory;
use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Sqidder;

/**
 * See the Pest::beforeEach hook for config setup.
 */
beforeEach(function () {
    $this->sqidder = new Sqidder(
        app(CoderFactory::class),
    );
});

it('implements the SqidsInterface interface', function () {
    expect($this->sqidder)->toBeInstanceOf(SqidsInterface::class);
});

it('implements the ConfigBasedSqidder interface', function () {
    expect($this->sqidder)->toBeInstanceOf(class: ConfigBasedSqidder::class);
});

it('can encode an array of numbers using the default config', function () {
    expect($this->sqidder->encode([1]))->toBe('Uk')
        ->and($this->sqidder->encode([1, 2, 3]))->toBe('86Rf07');
});

it('can decode a sqid using the default config', function () {
    expect($this->sqidder->decode('Uk'))->toBe([1])
        ->and($this->sqidder->decode('86Rf07'))->toBe([1, 2, 3]);
});

it('can provide a coder for a specific config name', function () {
    $coder = $this->sqidder->forConfig('other');

    expect($coder)->toBeInstanceOf(SqidsInterface::class)
        ->and($coder->encode([1]))->toBe('aa')
        ->and($coder->decode('aa'))->toBe([1]);
});

it('will use the default config when a null value is passed to the forConfig method', function () {
    $coder = $this->sqidder->forConfig();

    expect($coder)->toBeInstanceOf(SqidsInterface::class)
        ->and($coder->encode([1]))->toBe('Uk')
        ->and($coder->decode('Uk'))->toBe([1]);
});

it('has a facade', function () {
    expect(\StevenFox\LaravelSqids\Facades\Sqidder::getFacadeRoot())
        ->toBeInstanceOf(Sqidder::class);
});
