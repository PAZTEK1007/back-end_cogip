<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;
use App\Core\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

$router = new Router();

$router->mount('/api', function () use ($router) {
    // GET METHOD  //////////////////////////////////////////////////////
    // USERS /////////////////////////////////////////////////////////////////
    $router->get('/users', function () {
        $user = new HomeController();
        $response = $user->allUsers();
        Controller::sendJsonResponse($response);
    });

    $router->get('/fiveusers', 'HomeController@fiveUsers');
    $router->get('/users/(\d+)', 'HomeController@showUser');

    // COMPANIES /////////////////////////////////////////////////////////////////
    $router->get('/companies', 'HomeController@allCompanies');
    $router->get('/fivecompanies', 'HomeController@fiveCompanies');
    $router->get('/companies/(\d+)', 'HomeController@showCompany');

    // INVOICES /////////////////////////////////////////////////////////////////
    $router->get('/invoices', 'HomeController@allInvoices');
    $router->get('/fiveinvoices', 'HomeController@fiveInvoices');
    $router->get('/invoices/(\d+)', 'HomeController@showInvoice');

    // CONTACTS /////////////////////////////////////////////////////////////////
    $router->get('/contacts', 'HomeController@allContacts');
    $router->get('/fivecontacts', 'HomeController@fiveContacts');
    $router->get('/contacts/(\d+)', 'HomeController@showContact');

    // POST METHOD  ////////////////////////////////////////////////////////////////
    // COMPANY  /////////////////////////////////
    $router->post('/add-company', 'HomeController@createNewCompany');

    // CONTACT /////////////////////////////////////////
    $router->post('/add-contact', 'HomeController@createNewContact');

    // INVOICE ////////////////////////////////////////////
    $router->post('/add-invoice', 'HomeController@createNewInvoice');
});

$router->run();
