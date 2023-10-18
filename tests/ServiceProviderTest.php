<?php

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\CoderFactory;
use StevenFox\LaravelSqids\Contracts\ConfigRepository;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
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

it('binds a singleton Sqidder to the app container', function () {
    $sqidder = app('sqidder');

    expect($sqidder)
        ->toBeInstanceOf(SqidsManager::class)
        ->toBeInstanceOf(SqidsInterface::class)
        ->toBeInstanceOf(Sqidder::class)
        ->and(app(SqidsInterface::class))
        ->toBe($sqidder)
        ->and(app(SqidsManager::class))
        ->toBe($sqidder);
});
