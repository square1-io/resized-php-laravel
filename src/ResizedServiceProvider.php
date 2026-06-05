<?php

namespace Square1\Laravel\Resized;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Square1\Resized\Resized;

class ResizedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $source = dirname(__DIR__) . '/config/resized.php';

        $this->mergeConfigFrom($source, 'resized');

        if (empty(config('resized.key')) || empty(config('resized.secret'))) {
            Log::debug('Resizer not bound - key and secret config values required.');
            $resizer = new LocalResized(config('resized.key'), config('resized.secret'));
            $resizer->setEnabled(false);
        } elseif (config('environment') === 'local') {
            $resizer = new LocalResized(config('resized.key'), config('resized.secret'));
        } else {
            $resizer = new Resized(config('resized.key'), config('resized.secret'));
        }

        $this->app->bind('resized', function () use ($resizer) {
            $resizer->setHost(config('resized.host'));
            $resizer->setDefaultImage(config('resized.default'));
            $resizer->setDefaultOptions(config('resized.options'));

            return $resizer;
        });
    }
}
