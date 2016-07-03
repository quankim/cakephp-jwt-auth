<?php
use Cake\Routing\Router;

Router::plugin(
    'QuanKim/JwtAuth',
    ['path' => '/auth'],
    function ($routes) {
        $routes->connect('/token',['controller'=>'Users','action'=>'token']);
        $routes->fallbacks('DashedRoute');
    }
);
