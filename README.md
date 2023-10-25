# Sqids for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/steven-fox/laravel-sqids.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-sqids)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-sqids/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/steven-fox/laravel-sqids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/steven-fox/laravel-sqids/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/steven-fox/laravel-sqids/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/steven-fox/laravel-sqids.svg?style=flat-square)](https://packagist.org/packages/steven-fox/laravel-sqids)

This package brings Sqids functionality, with the option of multiple named configurations (alphabet and minimum encoding length), to your Laravel application. It's a batteries-included wrapper for the PHP Sqids package.

### Features
- Uses the PHP Sqids implementation under the hood, so features like custom alphabets, minimum encoded sqid lengths, and blocklists are available with this package.
- Permits multiple sqid configurations that can be referenced by name to use for decoding/encoding with a specific alphabet and minLength.
- Makes it easy to validate an encoded Sqid to ensure it's canonical (in other words, to ensure it is an original Sqid that was generated with your alphabet configuration) when decoding.
- Makes it easy to work with single integers for the common use case of encoding/decoding numeric database IDs.
- Makes it easy to extend the package's EncodedSqid & DecodedSqid objects so you can create dedicated Sqid classes for your custom configurations (meaning you can have a set of Sqids that are always used for a particular Model or other use case).

Here are some quick examples.

```php
$idFromDatabase = 7391;

$decodedSqid = \StevenFox\LaravelSqids\Sqids\DecodedSqid::new($idFromDatabase);
$encodedSqid = $decodedSqid->encode(); // instance of EncodedSqid

echo $encodedSqid->id(); // '2h2L'

$decodeAgain = $encodedSqid->decode(); // instance of DecodedSqid

echo $decodeAgain->numbers(); // [7391]
echo $decodeAgain->toInt(); // 7391
```

```php
$sqidFromRequest = '2h2L';

$encodedSqid = \StevenFox\LaravelSqids\Sqids\EncodedSqid::new($sqidFromRequest);
$decodedSqid = $encodedSqid->decode(); // instance of DecodedSqid

echo $decodedSqid->numbers(); // [7391]
echo $decodedSqid->toInt(); // 7391
```

```php
\StevenFox\LaravelSqids\Facades\Sqidder::encode([7391]); // '2h2L'
\StevenFox\LaravelSqids\Facades\Sqidder::decode('2h2L'); // [7391]
```

## Installation

You can install the package via composer:

```bash
composer require steven-fox/laravel-sqids
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-sqids-config"
```

Once the config file has been published, it is recommended to set your custom sqid configurations. This package allows you to use one or more named sqid configurations simultaneously and define one of them as a default. For each sqid configuration, you may specify a custom alphabet (which can provide a sense of uniqueness to your encoded sqids) and a minimum sqid length.

```php
// /config/sqids.php

return [
    // Specify the default Sqid configuration name.
    'default' => 'primary',

    // Specify one or more Sqid configurations that can be used as needed.
    'sqids' => [
    
        // This Sqid configuration will have a name of 'primary'.
        'primary' => [
            // Randomize IDs by specifying a custom/shuffled alphabet.
            'alphabet' => 'MGZAJbNxVrhm5Sz47URHwXQf1FPgvlc2ptjY9uLEianODyKCosBIkd0q3W6eT8',

            // Enforce a minimum length for the encoded Sqid.
            'minLength' => 0,
        ],
        
        // This Sqid configuration has a name of 'other'.
        'other' => [
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            'minLength' => 0,
        ],        
        
        // Perhaps this Sqid configuration is used for encoding/decoding User records.
        'user' => [
            'alphabet' => 'kQZlcRHnxL4GY6JW3Ir1weDK8MbOBztmqg7d02jp9UXyNTiSa5FEsvVPhAuofC',
            'minLength' => 0,
        ],
    ],

    // Prevent specific words from appearing anywhere in the encoded Sqids.
    'blocklist' => [
        '0rgasm',
        '1d10t',
        '1d1ot',
        ...
    ],
];
```

## Usage

### Prerequisite: Review the Sqids documentation
Although this package is easy to use, it's recommended to review the [Sqids documentation](https://sqids.org/) to understand the underlying functionality. There are a number of [good use cases for Sqids](https://sqids.org/faq#recommended), but there are also many where [they should **not** be used](https://sqids.org/faq#not-recommended) (for example, as encryption or to encode sensitive numbers).

Consider using the [playground](https://sqids.org/playground) to get a feel for the encoded Sqids created by different alphabet + minLength combinations.

### The Intended Pattern
It's intended to primarily use extensions of the `EncodedSqid` and `DecodedSqid` classes in your application.
For example:
- You create a `DecodedSqid` using the numeric `id` of a model and then you `encode()` that, ultimately providing a string you can use somewhere (like in a url).
- When your app handles something that involves a Sqid string (like a request url), you create an `EncodedSqid` instance using the provided Sqid string and then you `decode()`, ultimately yielding the numeric model `id` you need to fetch from the database.

If you plan on using multiple Sqid configurations (for example, one configuration per model), then you will likely want to create your own Encoded/Decoded Sqid classes - one for each configuration - that extend the `EncodedSqid` and `DecodedSqid` classes provided by this package. By doing this, you can instantiate your objects with the correct Sqid configuration "baked in", meaning you will not have to call the `$encodedSqid->usingConfigName('user-model')->...` method everywhere.

Here's a simple example:
```php

use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

class UserDecodedSqid extends DecodedSqid
{
    public const CONFIG_NAME = 'user';
    public const ENCODED_SQID_CLASS = UserEncodedSqid::class;
}
```
With that in place, you can now instantiate your `UserDecodedSqid` and the `'user'` configuration will be used automatically. Once you perform an `encode()` operation, the resulting object will be an instance of your `UserEncodedSqid`, which will also reference the `'user'` configuration automatically.

```php
$sqid = UserDecodedSqid::new($userId)->encode(); // instance of UserEncodedSqid
```

Making a custom `EncodedSqid` is essentially the same process.

### Using EncodedSqids


### Using the DecodedSqid

### Using the Sqidder
In this package, the `Sqidder` (pronounced squid-er) is a Sqids implementation class that can perform encoding and decoding based on a Sqid configuration. At its core, it is a wrapper for the `SqidsInterface` implementation. Thus, it provides the `encode()` and `decode()` interface methods, along with an additional method from the `ConfigBasedSqidder` contract (original to this package) that permit selection of a specific `coder` for a particular Sqid configuration.

The `EncodedSqid` and `DecodedSqid` classes use the `Sqidder` internally and that is meant to be the primary interaction. However, it is also possible to use the `Sqidder` directly and a Facade is provided for easy access.

```php
use StevenFox\LaravelSqids\Facades\Sqidder

// When a specific coder isn't specified,
// the coder for the default configuration will be used.
// The /config/sqids.php file above specifies the configuration
// named 'primary' should be used as the default.
Sqidder::encode([1]); // 'Ko'
Sqidder::encode([1, 2, 3]); // 'xjECV2'

Sqidder::decode('Ko'); // [1]
Sqidder::decode('xjECV2'); // [1, 2, 3]

// Specifying a config by name.
// The configuration *must* exist in the sqids config file.
// A \StevenFox\LaravelSqids\Exceptions\NamedSqidConfigurationNotFoundException will be thrown otherwise.
Sqidder::forConfig('other'); // A concrete SqidsInterface instance for the 'other' configuration.
Sqidder::forConfig('other')->encode([1]); // 'Uk'
Sqidder::forConfig('other')->decode('Uk'); // [1]

Sqidder::forConfig('does-not-exist'); // exception thrown
```

#### Canonical Sqids
The `Sqidder` does **not** perform Sqid validation when decoding to check if the encoded string is canonical. The `EncodedSqid`, however, does provide a validation option and is enabled by default when decoding.
> Due to the Sqids algorithm, multiple encoded strings may decode to the same number(s). Thus, a _canonical_ Sqid is one that originally came from your alphabet configuration. You can check if an encoded Sqid is canonical by decoding it into its number(s) and then re-encoding those numbers. If the original Sqid and the re-encoded Sqid are identical, then the original is canonical.
> 
> For example, using a standard alphabet, the encoded string 'Ul' will decode to the number [41]. However, re-encoding the number [41] produces a Sqid of 'qp'. Thus, the original Sqid of 'Ul' is not canonical and, in many circumstances, should be considered invalid.

### Using Multiple Sqid Configurations

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Steven Fox](https://github.com/steven-fox)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
