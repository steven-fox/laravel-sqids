<?php

use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Exceptions\DefaultSqidConfigurationNotSetException;
use StevenFox\LaravelSqids\Exceptions\NamedSqidConfigurationNotFoundException;

beforeEach(function () {
    $this->repo = new ConfigRepository(config());
});

it('implements the ConfigRepository interface', function () {
    expect($this->repo)->toBeInstanceOf(\StevenFox\LaravelSqids\Contracts\ConfigRepository::class);
});

it('can determine if a sqid config exists for a given name', function () {
    expect($this->repo->hasSqidConfig('foo'))
        ->toBeFalse()
        ->and($this->repo->hasSqidConfig('primary'))
        ->toBeTrue();
});

it('can get the sqid configuration for a config name', function () {
    $sqidConfig = $this->repo->getSqidConfig('primary');

    expect($sqidConfig)
        ->toBeInstanceOf(SqidConfiguration::class)
        ->and($sqidConfig->name)->toBe('primary')
        ->and($sqidConfig->alphabet)->toBe('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
        ->and($sqidConfig->minLength)->toBe(0)
        ->and($sqidConfig->blocklist)->not()->toBeEmpty();
});

it("will throw an exception when trying to get a named sqid configuration that doesn't exist", function () {
    $this->expectException(NamedSqidConfigurationNotFoundException::class);

    $this->repo->getSqidConfig('foo');
});

it('can set a sqid configuration', function () {
    expect($this->repo->hasSqidConfig('foo'))->toBeFalse();

    $sqidConfig = new SqidConfiguration(
        name: 'foo',
        alphabet: 'abc',
        minLength: 0,
        blocklist: [],
    );

    $this->repo->setSqidConfig($sqidConfig);

    $fooConfig = $this->repo->getSqidConfig('foo');

    expect($fooConfig)
        ->toBeInstanceOf(SqidConfiguration::class)
        ->and($fooConfig->name)->toBe('foo')
        ->and($fooConfig->alphabet)->toBe('abc')
        ->and($fooConfig->minLength)->toBe(0)
        ->and($fooConfig->blocklist)->not()->toBeEmpty();
});

it('can get the global blocklist', function () {
    expect($this->repo->getBlocklist())
        ->toBeArray()
        ->toContain('1d10t');
});

it('can get the default sqid config name', function () {
    expect($this->repo->defaultSqidConfigKey())
        ->toBe('primary');
});

it('will throw an exception when attempting to get a null default sqid config name', function () {
    config()->set('sqids.default', null);

    $this->expectException(DefaultSqidConfigurationNotSetException::class);

    $this->repo->defaultSqidConfigKey();
});

it('can set the default sqid config name', function () {
    $this->repo->setDefaultSqidConfigKey('other');

    expect($this->repo->defaultSqidConfigKey())
        ->toBe('other');
});

it("will throw an exception when trying to set a default sqid config name that doesn't exist", function () {
    $this->expectException(NamedSqidConfigurationNotFoundException::class);

    $this->repo->setDefaultSqidConfigKey('foo');
});

it('can get the default sqid configuration', function () {
    $defaultConfig = $this->repo->defaultSqidConfig();

    expect($defaultConfig)
        ->toBeInstanceOf(SqidConfiguration::class)
        ->and($defaultConfig->name)->toBe('primary')
        ->and($defaultConfig->alphabet)->toBe('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
        ->and($defaultConfig->minLength)->toBe(0)
        ->and($defaultConfig->blocklist)->not()->toBeEmpty();
});

it('can get the root config key', function () {
    expect($this->repo->rootConfigName())
        ->toBe('sqids');
});

it('can get a config value at a given key path', function () {
    expect($this->repo->getConfigValue('default'))
        ->toBe('primary')
        ->and($this->repo->getConfigValue('sqids'))
        ->toBeArray()
        ->toHaveCount(2);
});

it('can set a config value at a given key path', function () {
    $this->repo->setConfigValue('default', 'other');

    expect($this->repo->getConfigValue('default'))
        ->toBe('other');

    $this->repo->setConfigValue('sqids.primary.alphabet', 'abc');

    expect($this->repo->getConfigValue('sqids.primary.alphabet'))
        ->toBe('abc');
});
