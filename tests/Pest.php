<?php

use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        $repo = new ConfigRepository(config());
        $repo->setSqidConfig(new SqidConfiguration(
            name: 'primary',
            alphabet: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            minLength: 0,
            blocklist: [], // won't be set as we use a global blacklist
        ));
        $repo->setSqidConfig(new SqidConfiguration(
            name: 'other',
            alphabet: 'abc',
            minLength: 0,
            blocklist: [], // won't be set as we use a global blacklist
        ));
        $repo->setDefaultSqidConfigKey('primary');
    })
    ->in(__DIR__);
