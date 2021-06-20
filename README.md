[![Latest Version on Packagist](https://img.shields.io/packagist/v/tkaratug/laravel-domain-whitelist.svg?style=flat-square)](https://packagist.org/packages/tkaratug/laravel-domain-whitelist)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/tkaratug/laravel-domain-whitelist/run-tests?label=tests)](https://github.com/tkaratug/laravel-domain-whitelist/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/tkaratug/laravel-domain-whitelist/Check%20&%20fix%20styling?label=code%20style)](https://github.com/tkaratug/laravel-domain-whitelist/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tkaratug/laravel-domain-whitelist.svg?style=flat-square)](https://packagist.org/packages/tkaratug/laravel-domain-whitelist)

---
## Introduction
This package contains a middleware to check whether the origin domain is in the whitelist. If not it blocks the request.

---
## Installation

You can install the package via composer:

```bash
composer require tkaratug/laravel-domain-whitelist
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Tkaratug\LaravelDomainWhitelist\LaravelDomainWhitelistServiceProvider" --tag="domain-whitelist-config"
```

This is the contents of the published config file:

```php
return [
    /*
    |---------------------------------------------
    | Domains to allow
    | Leave empty to allow all requests
    |---------------------------------------------
    */
    'domains' => [
        //'*.example.com',
        //'example.com',
    ],

    /*
    |---------------------------------------------
    | Paths to exclude
    |---------------------------------------------
    */
    'excludes' => [
        //'/api/posts',
    ],
];
```
---
## Usage
Packages comes with DomainWhitelist middleware. You can register it in `$routeMiddleware` in `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    // ...
    'domain_whitelist' => \Tkaratug\LaravelDomainWhitelist\Middlewares\DomainWhitelist::class,
];
```

Use the middleware in any of your routes.
```php
Route::middleware('domain_whitelist')->get('/', [HomeController::class, 'index']);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Turan Karatuğ](https://github.com/tkaratug)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
