<?php

namespace StevenFox\LaravelSqids;

use Illuminate\Contracts\Config\Repository;
use StevenFox\LaravelSqids\Exceptions\DefaultSqidConfigurationNotSetException;
use StevenFox\LaravelSqids\Exceptions\NamedSqidConfigurationNotFoundException;

readonly class ConfigRepository
{
    public function __construct(private Repository $config)
    {
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

    public function setDefaultSqidConfigKey(string $name): static
    {
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

    private function getConfigValue(string $path, mixed $default = null): mixed
    {
        return $this->config->get($this->rootConfigName().'.'.$path, $default);
    }
}
