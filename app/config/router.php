<?php

$router = $di->getRouter(false);

// Define your routes here
$router->addPost(
    '/login',
    [
        'controller' => 'user',
        'action'     => 'login'
    ]
)->setName('login');

$router->mount(new UserRoutes());

// Establecer camino 404
$router->notFound(
    [
        'controller' => 'index',
        'action'     => 'route404',
    ]
);

// Quitar barras de arrastre automáticamente
$router->removeExtraSlashes(true);

$router->handle();
