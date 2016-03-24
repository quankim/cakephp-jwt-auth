<?php
use Cake\Routing\Router;

Router::plugin(
    'QuanKim/JwtAuth',
    ['path' => '/QuanKim/JwtAuth'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
