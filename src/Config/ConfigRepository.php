<?php

namespace StevenFox\LaravelSqids\Config;

use Illuminate\Contracts\Config\Repository;
use StevenFox\LaravelSqids\Contracts\ConfigRepository as ConfigRepositoryInterface;
use StevenFox\LaravelSqids\Exceptions\DefaultSqidConfigurationNotSetException;
use StevenFox\LaravelSqids\Exceptions\NamedSqidConfigurationNotFoundException;

readonly class ConfigRepository implements ConfigRepositoryInterface
{
    public function __construct(private Repository $config)
    {
    }

    public function hasSqidConfig(string $name): bool
    {
        return filled($this->getConfigValue('sqids.'.$name));
    }

    public function getSqidConfig(string $name): SqidConfiguration
    {
        $config = $this->getConfigValue('sqids.'.$name);

        if (empty($config)) {
            throw NamedSqidConfigurationNotFoundException::make($name);
        }

        return new SqidConfiguration(
            name: $name,
            alphabet: $config['alphabet'],
            minLength: $config['minLength'],
            blocklist: $this->getBlocklist(),
        );
    }

    public function setSqidConfig(SqidConfiguration $config): ConfigRepositoryInterface
    {
        return $this->setConfigValue(
            'sqids.'.$config->name,
            [
                'alphabet' => $config->alphabet,
                'minLength' => $config->minLength,
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getBlocklist(): array
    {
        return $this->getConfigValue('blocklist', []);
    }

    public function defaultSqidConfigKey(): string
    {
        $key = $this->getConfigValue('default');

        return $key ?: throw DefaultSqidConfigurationNotSetException::make();
    }

    public function setDefaultSqidConfigKey(string $name): ConfigRepositoryInterface
    {
        if (! $this->hasSqidConfig($name)) {
            throw NamedSqidConfigurationNotFoundException::make($name);
        }

        $this->config->set($this->rootConfigName().'.default', $name);

        return $this;
    }

    public function defaultSqidConfig(): SqidConfiguration
    {
        $key = $this->defaultSqidConfigKey();

        return $this->getSqidConfig($key);
    }

    public function rootConfigName(): string
    {
        return 'sqids';
    }

    public function getConfigValue(string $path, mixed $default = null): mixed
    {
        return $this->config->get($this->rootConfigName().'.'.$path, $default);
    }

    public function setConfigValue(string $path, mixed $value): ConfigRepositoryInterface
    {
        $this->config->set($this->rootConfigName().'.'.$path, $value);

        return $this;
    }
}
