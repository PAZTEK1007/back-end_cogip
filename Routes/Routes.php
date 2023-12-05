<?php

namespace App\Routes;

use Bramus\Router\Router;

use App\Controllers\HomeController;
use App\Controllers\PizzaController;

use App\Core\Data;

$router = new Router();

$router->get('/', function() {
    (new HomeController)->index();
    $bdd = new Data('localhost', 'cogip_project', 'root', 'root');
    $pdo = $bdd->connect();
});

$router->get('/pizza', function() {
    (new PizzaController)->index();
});

$router->run();
