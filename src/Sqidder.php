<?php

namespace StevenFox\LaravelSqids;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\SqidsManager;

class Sqidder implements SqidsManager
{
    /** @var SqidsInterface[] */
    private array $coders;

    public function __construct(
        private Contracts\ConfigRepository $configRepository,
        private Contracts\CoderFactory $coderFactory,
    ) {
    }

    public function encode(array $numbers): string
    {
        return $this->coderForDefaultConfig()->encode($numbers);
    }

    public function decode(string $id): array
    {
        return $this->coderForDefaultConfig()->decode($id);
    }

    public function coderForDefaultConfig(): SqidsInterface
    {
        return $this->coder($this->defaultConfigName());
    }

    public function coderForSqidConfigName(string $configName): SqidsInterface
    {
        return $this->coder($configName);
    }

    public function coderForSqidConfig(SqidConfiguration $config): SqidsInterface
    {
        return $this->coder($config);
    }

    private function coder(string|SqidConfiguration $config): SqidsInterface
    {
        $config = $this->resolveConfig($config);
        $configName = $config->name;

        if (! isset($this->coders[$configName])) {
            $this->coders[$configName] = $this->makeCoder($config);
        }

        return $this->coders[$configName];
    }

    private function makeCoder(SqidConfiguration $config): SqidsInterface
    {
        return $this
            ->coderFactory
            ->makeForSqidConfig($config);
    }

    private function resolveConfig(string|SqidConfiguration $config): SqidConfiguration
    {
        if ($config instanceof SqidConfiguration) {
            return $config;
        }

        $configName = $this->resolveConfigName($config);

        return $this->configRepository->getSqidConfig($configName);
    }

    private function resolveConfigName(string|SqidConfiguration $config): string
    {
        return match (true) {
            $config instanceof SqidConfiguration => $config->name,
            blank($config) => $this->defaultConfigName(),
            default => $config,
        };
    }

    private function defaultConfigName(): string
    {
        return $this->configRepository->defaultSqidConfigKey();
    }
}
