<?php

namespace StevenFox\LaravelSqids\Factories;

use Sqids\Sqids;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\SqidConfiguration;
use StevenFox\LaravelSqids\Contracts\CoderFactory as CoderFactoryInterface;
use StevenFox\LaravelSqids\Contracts\ConfigRepository;

class CoderFactory implements CoderFactoryInterface
{
    public function __construct(
        private ConfigRepository $configRepository
    ) {
    }

    public function forConfig(SqidConfiguration $config): SqidsInterface
    {
        return new Sqids(
            alphabet: $config->alphabet,
            minLength: $config->minLength,
            blocklist: $config->blocklist,
        );
    }

    public function forDefaultConfig(): SqidsInterface
    {
        return $this->forConfig($this->configRepository->defaultSqidConfig());
    }

    public function forConfigName(string $name): SqidsInterface
    {
        return $this->forConfig($this->configRepository->getSqidConfig($name));
    }
}
