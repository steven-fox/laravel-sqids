<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\CoderFactory;
use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Contracts\ConfigRepository;
use StevenFox\LaravelSqids\Sqidder;

it('binds the config repository to the app container', function () {
    expect(app(ConfigRepository::class))
        ->toBeInstanceOf(ConfigRepository::class)
        ->toBeInstanceOf(\StevenFox\LaravelSqids\Config\ConfigRepository::class);
});

it('binds the coder factory to the app container', function () {
    expect(app(CoderFactory::class))
        ->toBeInstanceOf(CoderFactory::class)
        ->toBeInstanceOf(\StevenFox\LaravelSqids\Factories\CoderFactory::class);
});

it('binds a Sqidder to the app container via the "sqidder" name', function () {
    $sqidder = app('sqidder');

    expect($sqidder)
        ->toBeInstanceOf(ConfigBasedSqidder::class)
        ->toBeInstanceOf(SqidsInterface::class)
        ->toBeInstanceOf(Sqidder::class);
});

it('binds a Sqidder to the app container via the SqidsInterface abstract', function () {
    $sqidder = app(SqidsInterface::class);

    expect($sqidder)
        ->toBeInstanceOf(ConfigBasedSqidder::class)
        ->toBeInstanceOf(SqidsInterface::class)
        ->toBeInstanceOf(Sqidder::class);
});

it('binds a Sqidder to the app container via the ConfigBasedSqidder abstract', function () {
    $sqidder = app(ConfigBasedSqidder::class);

    expect($sqidder)
        ->toBeInstanceOf(ConfigBasedSqidder::class)
        ->toBeInstanceOf(SqidsInterface::class)
        ->toBeInstanceOf(Sqidder::class);
});
