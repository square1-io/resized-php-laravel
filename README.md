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

You can also publish the config file to set default processing options applied to every `process()` call:

```php
'options' => [
    'quality' => 90,
],
```

When `RESIZED_KEY` or `RESIZED_SECRET` are not set, the service falls back to a local pass-through mode that returns URLs unchanged, so your application does not crash in environments without credentials configured.

On a `local` environment (detected via the `environment` config key), the package always uses pass-through mode regardless of credentials, returning relative and absolute URLs unchanged.

## Usage

Set the default failover image.

```php
Resized::setDefaultImage('http://www.example.com/no-image.jpg');
```

Generate a 300x500 resized image URL.

```php
$url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300', '500');
```

Generate an image URL constrained to 300 width whilst maintaining aspect ratio.

```php
$url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300');
```

Generate an image URL constrained to 500 height whilst maintaining aspect ratio.

```php
$url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '500');
```

Override the image slug used in the generated URL.

```php
$url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '300', '500', 'A nice image name');
```

Specify the compression level through the options array. Quality ranges from 0 (poor quality, small file) to 100 (best quality, large file). Only applies to JPG encoding. Default: 90.

```php
$url = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '500', 'A nice image name', ['quality' => 100]);
```

Limit the maximum character length of the filename slug appended to the generated URL.

```php
Resized::setMaxSlugLength(50);
$url = Resized::process('http://www.example.com/some-very-long-image-filename.jpg', '300', '500');
```

Relative URLs and non-HTTP URLs are returned unchanged without making a request to the resized.co service.

```php
$url = Resized::process('/uploads/image.jpg', '300', '500');
// Returns '/uploads/image.jpg' unmodified
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
