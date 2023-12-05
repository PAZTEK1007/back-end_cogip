<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Controllers\ApiController;

$router = new Router();

$router->get('/api', function () {
    echo '<pre>';
    (new ApiController)->fetchUsers();
    echo '</pre>';
});



$router->run();
