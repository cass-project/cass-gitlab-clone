<?php
namespace Domain\Colors;

use Domain\Colors\Middleware\ColorsMiddleware;

return [
    'common' => [
        [
            'type'       => 'route',
            'method'     => 'get',
            'url'        => '/colors/{command:get-colors}[/]',
            'middleware' => ColorsMiddleware::class,
            'name'       => 'colors-get-colors'
        ],
        [
            'type'       => 'route',
            'method'     => 'get',
            'url'        => '/colors/{command:get-palettes}[/]',
            'middleware' => ColorsMiddleware::class,
            'name'       => 'colors-get-palettes'
        ],
    ]
];