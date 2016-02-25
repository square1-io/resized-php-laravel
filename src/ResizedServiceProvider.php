<?php

namespace Square1\Laravel\Resized;

use Square1\Resized\Resized;
use Illuminate\Support\ServiceProvider;

class ResizedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = dirname(__DIR__).'/config/resized.php';

        $this->mergeConfigFrom($source, 'resized');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('resized', function () {
            $resizer = new Resized(config('resized.key'), config('resized.secret'));
            $resizer->setHost(config('resized.host'));
            $resizer->setDefaultImage(config('resized.default'));

            return $resizer;
        });
    }
}
