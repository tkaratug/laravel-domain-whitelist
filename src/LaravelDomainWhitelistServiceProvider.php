<?php

namespace Tkaratug\LaravelDomainWhitelist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDomainWhitelistServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-domain-whitelist')
            ->hasConfigFile();
    }
}
