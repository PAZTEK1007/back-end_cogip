<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;

$router = new Router();

$router->get('/api/users', function () {
    (new HomeController)->users();
});

$router->get('/api/fiveusers', function () {
    (new HomeController)->fiveUsers();
});

$router->get('/api/companies', function () {
    (new HomeController)->AllCompanies();
});

$router->get('/api/fivecompanies', function () {
    (new HomeController)->fiveCompanies();
});

$router->run();
