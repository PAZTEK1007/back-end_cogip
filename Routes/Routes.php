<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\UsersController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$router = new Router();

$router->mount('/api', function () use ($router) {

    // will result in '/users'
    $router->get('/users', function () {
        $controller = new UsersController();
        $response = $controller->getUsers();
        sendJsonResponse($response);
    });

    $router->post('/user', function () {
        $controller = new UsersController();
        $request = Request::createFromGlobals(); // Create a Request object
        $response = $controller->createUser($request);
        sendJsonResponse($response);
    });

    // will result in '/user/id'
    $router->get('/user/(\d+)', function ($id) {
        $controller = new UsersController();
        $response = $controller->getUser($id);
        sendJsonResponse($response);
        htmlentities($id);
    });

    // will result in '/movies/id'
    $router->put('/(\d+)', function ($id) {
        echo 'Update movie id ' . htmlentities($id);
    });
});
$router->run();

// Function to send JSON response
function sendJsonResponse($response)
{
    // Output the JSON response
    echo $response;

    // Set the HTTP status code and headers
    $statusCode = $response instanceof JsonResponse ? $response->getStatusCode() : Response::HTTP_OK;
    http_response_code($statusCode);

    foreach ($response->headers->all() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
}
