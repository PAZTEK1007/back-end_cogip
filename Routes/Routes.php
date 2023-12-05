<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;
use App\Queries\Database;

$router = new Router();

$router->get('/', function () {
    (new HomeController)->index();
    $bdd = new Database('localhost', 'cogip_project','root', 'root');
    $bdd->getConnection();
});

$router->run();
