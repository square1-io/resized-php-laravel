<?php

namespace Square1\Laravel\Resized;

use Log;
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
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $source = dirname(__DIR__).'/config/resized.php';

        $this->mergeConfigFrom($source, 'resized');

        if (empty(config('resized.key')) || empty(config('resized.secret'))) {
            Log::debug('Resizer not bound - key and secret config values required.');
            $resizer = new LocalResized(config('resized.key'), config('resized.secret'));
            $resizer->setEnabled(false);
        } else {
            if (config('environment') == 'local') {
                $resizer = new LocalResized(config('resized.key'), config('resized.secret'));
            } else {
                $resizer = new Resized(config('resized.key'), config('resized.secret'));
            }
        }

        $this->app->bind('resized', function () use ($resizer) {
            $resizer->setHost(config('resized.host'));
            $resizer->setDefaultImage(config('resized.default'));
            $resizer->setDefaultOptions(config('resized.options'));
            return $resizer;
        });
    }
}
