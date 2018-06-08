# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cappuc/rest-countries-php.svg?style=flat-square)](https://packagist.org/packages/cappuc/rest-countries-php)
[![Build Status](https://img.shields.io/travis/cappuc/rest-countries-php/master.svg?style=flat-square)](https://travis-ci.org/cappuc/rest-countries-php)
[![Total Downloads](https://img.shields.io/packagist/dt/cappuc/rest-countries-php.svg?style=flat-square)](https://packagist.org/packages/cappuc/rest-countries-php)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require cappuc/rest-countries-php
```

## Usage

``` php
$restCountriesApi = new Cappuc\RestCountries\RestCountriesApi($httpClient, $httpMessageFactory);

$restCountriesApi->all();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@cappuc.be instead of using the issue tracker.

## Credits

- [Fabio Capucci](https://github.com/cappuc)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
