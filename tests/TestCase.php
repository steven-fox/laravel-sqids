<?php

namespace StevenFox\LaravelSqids\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use StevenFox\LaravelSqids\LaravelSqidsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelSqidsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
