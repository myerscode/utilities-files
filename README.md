# Files Utility
> A PHP utility class that helps with common file tasks

[![Latest Stable Version](https://poser.pugx.org/myerscode/utilities-files/v/stable)](https://packagist.org/packages/myerscode/utilities-files)
[![Total Downloads](https://poser.pugx.org/myerscode/utilities-files/downloads)](https://packagist.org/packages/myerscode/utilities-files)
[![License](https://poser.pugx.org/myerscode/utilities-files/license)](https://packagist.org/packages/myerscode/utilities-files)
![Tests](https://github.com/myerscode/utilities-files/workflows/Tests/badge.svg?branch=master)

## Install

You can install this package via composer:

``` bash
composer require myerscode/utilities-files
```

## Usage

Create an instance of the fluent interface by creating a new instance either via `new Utility($val)` or `Utility::make($val)`

``` php
$fileHelper = new Utility('./src/file.php');

$fileHelper = Utility::make('./src/file.php');
```

## Methods

See all available methods in the [documentation](docs/methods.md).

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/utilities-files/issues).

## Contributing

We are very happy to receive pull requests to add functionality or fixes. Please read the Myerscode [contributing](https://github.com/myerscode/docs/blob/main/CONTRIBUTING.md) guide for information.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
