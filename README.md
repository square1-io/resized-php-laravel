# Resized

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


This is the Laravel package for resized.co, an on-demand image resize manipulation service.

Requires PHP 8.2 and Laravel 10 or higher.

## Install

Via Composer

``` bash
$ composer require square1/resized-laravel
```

The package supports auto-discovery. The service provider and facade are registered automatically.

To manually register the service provider in your `config/app.php`:

Find the `providers` key in your `config/app.php` and register the Resized Service Provider.

```php
    'providers' => array(
        // ...
        Square1\Laravel\Resized\ResizedServiceProvider::class,
    )
```

Find the `aliases` key in your `config/app.php` and add the Resized facade alias.

```php
    'aliases' => array(
        // ...
        'Resized' => Square1\Laravel\Resized\ResizedFacade::class,
    )
```

## Configuration

By default, the package uses the following environment variables to auto-configure the plugin without modification:

```
RESIZED_KEY
RESIZED_SECRET
RESIZED_DEFAULT_IMAGE
```

## Usage

Set the default failover image.

``` php
	Resized::setDefaultImage('http://www.example.com/no-image.jpg');
```

Generate a 300x500 resized image URL.

``` php
    $url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300', '500');
```

Generate an image URL that is contrained to 300 width whilst mantaining aspect ratio.

``` php
    $url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300', '');
```

Generate an image URL that is contrained to 500 height whilst mantaining aspect ratio.

``` php
    $url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '500');
```

Override image slug.

``` php
    $url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300', '500', 'A nice image name');
```

Specify the compression level through the options array.
>Data ranging from 0 (poor quality, small file) to 100 (best quality, big file). Quality is only applied if you're encoding JPG format since PNG compression is lossless and does not affect image quality. Default: 90.

``` php
    $url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '500', 'A nice image name', ['quality' => 100]);
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/square1/resized-laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/square1/resized-laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/square1/resized-laravel
[link-downloads]: https://packagist.org/packages/square1/resized-laravel
