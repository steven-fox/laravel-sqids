<?php

namespace StevenFox\LaravelSqids;

use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\SqidsManager;

class Sqidder implements SqidsManager
{
    /** @var SqidsInterface[] */
    private array $coders;

    public function __construct(
        private ConfigRepository $configRepository,
        private CoderFactory $coderFactory,
    ) {
    }

    public function encode(array $numbers): string
    {
        return $this->coder()->encode($numbers);
    }

    public function decode(string $sqid): array
    {
        return $this->coder()->decode($sqid);
    }

    public function coderForSqidConfig(string $configName = null): SqidsInterface
    {
        return $this->coder($configName);
    }

    private function defaultConfigName(): string
    {
        return $this->configRepository->defaultSqidConfigKey();
    }

    private function coder(string $configName = null): SqidsInterface
    {
        $configName = $configName ?: $this->defaultConfigName();

        if (! isset($this->coders[$configName])) {
            $this->coders[$configName] = $this->makeCoder($configName);
        }

        return $this->coders[$configName];
    }

    private function makeCoder(string $configName): SqidsInterface
    {
        return $this
            ->coderFactory
            ->makeForSqidConfig(
                $this->configRepository->getSqidConfig($configName)
            );
    }
}
