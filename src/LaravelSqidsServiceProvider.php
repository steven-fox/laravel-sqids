<?php

namespace StevenFox\LaravelSqids;

use Illuminate\Contracts\Container\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sqids\SqidsInterface;
use StevenFox\LaravelSqids\Contracts\SqidsManager;
use StevenFox\LaravelSqids\Facades\Sqidder as SqidderFacade;

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
        $this->app->singleton('sqidder', function (Container $app) {
            $sqidConfigRepository = new ConfigRepository($app['config']);

            return new Sqidder($sqidConfigRepository, new CoderFactory());
        });

        $this->app->singleton(SqidsInterface::class, fn (Container $app) => $app['sqidder']);
        $this->app->singleton(SqidsManager::class, fn (Container $app) => $app['sqidder']);

        $this->app->alias('sqidder', SqidderFacade::class);
    }

    public function provides(): array
    {
        return [
            'sqidder',
            SqidsInterface::class,
            SqidsManager::class,
        ];
    }
}
