<?php

namespace StevenFox\LaravelSqids\Contracts;

use StevenFox\LaravelSqids\Config\SqidConfiguration;

interface ConfigRepository
{
    public function hasSqidConfig(string $name): bool;

    public function getSqidConfig(string $name): SqidConfiguration;

    public function setSqidConfig(SqidConfiguration $config): ConfigRepository;

    /**
     * @return string[]
     */
    public function getBlocklist(): array;

    public function defaultSqidConfigKey(): string;

    public function setDefaultSqidConfigKey(string $name): ConfigRepository;

    public function defaultSqidConfig(): SqidConfiguration;

    public function rootConfigName(): string;

    public function getConfigValue(string $path, mixed $default = null): mixed;

    public function setConfigValue(string $path, mixed $value): ConfigRepository;
}
