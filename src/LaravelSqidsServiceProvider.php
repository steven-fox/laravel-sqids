<?php

namespace StevenFox\LaravelSqids;

use Illuminate\Contracts\Container\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Config\ConfigRepository;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Facades\Sqidder as SqidderFacade;
use StevenFox\LaravelSqids\Factories\CoderFactory;

class LaravelSqidsServiceProvider extends PackageServiceProvider
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
            return new CoderFactory();
        });

        $this->app->singleton('sqidder', function (Container $app) {
            return new Sqidder(
                $app->make(Contracts\ConfigRepository::class),
                $app->make(Contracts\CoderFactory::class),
            );
        });

        $this->app->singleton(SqidsInterface::class, fn (Container $app) => $app['sqidder']);
        $this->app->singleton(SqidsManager::class, fn (Container $app) => $app['sqidder']);
        $this->app->alias('sqidder', SqidderFacade::class);
    }

    public function provides(): array
    {
        return [
            Contracts\ConfigRepository::class,
            Contracts\CoderFactory::class,
            'sqidder',
            SqidsInterface::class,
            SqidsManager::class,
        ];
    }
}
