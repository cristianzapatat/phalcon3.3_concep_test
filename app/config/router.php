<?php

//include __DIR__ . '/../routes/UserRoutes.php';
use Phalcon\Mvc\Router\Group as RouterGroup;

$router = $di->getRouter(false);

// Define your routes here

$router->add(
    '/users',
    [
        'controller' => 'user',
        'action'     => 'index'
    ]
);

$router->add(
    '/get',
    [
        'controller' => 'index',
        'action'     => 'test',
    ]
)->via(['POST', 'GET']);

$user = new RouterGroup(
    [
        'controller' => 'user'
    ]
);

$user->setPrefix('/user');
$user->add(
    '[/]{0,1}',
    [
        'action' => 'list'
    ]
);

$router->mount(
    $user
);

$router->mount(
    new UserRoutes()
);

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
