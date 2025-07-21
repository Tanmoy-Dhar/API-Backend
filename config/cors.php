<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // ['GET', 'POST', 'PUT', 'DELETE', ...]

    'allowed_origins' => [
        'http://localhost:5173',  // Vite dev server (HTTP)
        'https://localhost:5173', // Vite dev server (HTTPS if configured)
        'http://127.0.0.1:5173',  // Alternative localhost
        'https://127.0.0.1:5173'  // Alternative localhost (HTTPS)
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // e.g. ['Content-Type', 'X-Requested-With']

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
    