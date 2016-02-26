# Resized

This is the Laravel 5 package for resized.co, an on-demand image resize manipulation service.

## Install

Via Composer

``` bash
$ composer require square1/resized-laravel
```

To use the Resized Service Provider, you must register the provider when bootstrapping your Laravel application.

Find the `providers` key in your `config/app.php` and register the Resized Service Provider.

```php
    'providers' => array(
        // ...
        Square1\Laravel\Resized\ResizedServiceProvider::class,
    )
```

Find the `aliases` key in your `config/app.php` and add the AWS facade alias.

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
RESIZED_DEFAULT
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

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
