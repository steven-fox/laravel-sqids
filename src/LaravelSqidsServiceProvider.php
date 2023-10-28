<?php

namespace StevenFox\LaravelSqids;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Contracts\ConfigBasedSqidder;
use StevenFox\LaravelSqids\Facades\Sqidder as SqidderFacade;
use StevenFox\LaravelSqids\Factories\CoderFactory;

class LaravelSqidsServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-sqids')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(Contracts\ConfigRepository::class, function (Container $app) {
            return new ConfigRepository(config());
        });

        $this->app->bind(Contracts\CoderFactory::class, function (Container $app) {
            return new CoderFactory($app->make(Contracts\ConfigRepository::class));
        });

        $this->app->bind('sqidder', function (Container $app) {
            return new Sqidder($app->make(Contracts\CoderFactory::class));
        });

        $this->app->alias('sqidder', SqidsInterface::class);
        $this->app->alias('sqidder', ConfigBasedSqidder::class);
        $this->app->alias('sqidder', SqidderFacade::class);
    }

    public function provides(): array
    {
        return [
            Contracts\ConfigRepository::class,
            Contracts\CoderFactory::class,
            'sqidder',
            SqidsInterface::class,
            ConfigBasedSqidder::class,
        ];
    }
}
