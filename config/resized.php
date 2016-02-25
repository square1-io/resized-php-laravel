<?php

return [
    'key' => env('RESIZED_KEY'),
    'secret' => env('RESIZED_SECRET'),
    'host' => env('RESIZED_HOST', 'https://img.resized.co'),
    'default' => env('RESIZED_DEFAULT', 'https://cache.resized.co/no-image.png'),
];
