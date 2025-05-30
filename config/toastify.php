<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Toastify CDN links
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CDN links for the toastify library.
    |
    */

    'cdn' => [
        'js' => '/vendor/toastify/toastify.min.js',
        'css' => '/vendor/toastify/toastify.min.css',
    ],

    /*
    |--------------------------------------------------------------------------
    | Toastify Toastifiers Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the toastifiers options for the toastify library.
    | Each toastifier will be available as a method in the Toastify facade.
    |
    */

    'toastifiers' => [
        'toast' => [
            'style' => [
                'color' => 'var(--tblr-body)',
                'background' => 'var(--tblr-body-bg)',
                'border' => '1px solid var(--tblr-border-color)',
            ],
        ],
        'error' => [
            'style' => [
                'color' => 'var(--tblr-danger)',
                'background' => 'var(--tblr-danger-lt)',
                'border' => '1px solid var(--tblr-danger)',
            ],
        ],
        'success' => [
            'style' => [
                'color' => 'var(--tblr-success)',
                'background' => 'var(--tblr-success-lt)',
                'border' => '1px solid var(--tblr-success)',
            ],
        ],
        'info' => [
            'style' => [
                'color' => 'var(--tblr-info)',
                'background' => 'var(--tblr-info-lt)',
                'border' => '1px solid var(--tblr-info)',
            ],
        ],
        'warning' => [
            'style' => [
                'color' => 'var(--tblr-warning)',
                'background' => 'var(--tblr-warning-lt)',
                'border' => '1px solid var(--tblr-warning)',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Toastify Default Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default options for the toastify library.
    |
    */

    'defaults' => [
        'gravity' => 'toastify-bottom',
        'position' => 'right',
        'close' => true,
    ],
];
