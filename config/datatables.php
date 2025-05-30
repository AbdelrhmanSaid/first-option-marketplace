<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redot Datatables config
    |--------------------------------------------------------------------------
    |
    | Here you can specify the configuration of the redot datatable.
    |
    */

    'assets' => [
        'css' => [
            'file' => base_path('vendor/redot/datatables/resources/css/datatables.css'),
            'uri' => 'datatables/datatables.css',
            'route' => 'datatables.css',
        ],
        'js' => [
            'file' => base_path('vendor/redot/datatables/resources/js/datatables.js'),
            'uri' => 'datatables/datatables.js',
            'route' => 'datatables.js',
        ],
    ],

    'export' => [
        'xlsx' => [
            'enabled' => true,
        ],

        'csv' => [
            'enabled' => true,
        ],

        'json' => [
            'enabled' => true,
        ],

        'pdf' => [
            'enabled' => true,
            'template' => 'templates.pdf.datatable',
            'adapter' => \Redot\Datatables\Adapters\PDF\LaravelMpdf::class,

            'options' => [
                // Refer to config/pdf.php for more options
            ],
        ],
    ],
];
