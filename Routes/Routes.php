<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;

$router = new Router();

// GET METHOD  //////////////////////////////////////////////////////
// USERS /////////////////////////////////////////////////////////////////
$router->get('/api/users', function () {
    (new HomeController)->allUsers();
});
$router->get('/api/fiveusers', function () {
    (new HomeController)->fiveUsers();
});
$router->get('/api/users/(\d+)', function ($id) {
    (new HomeController)->showUser($id);
});


// COMPANIES /////////////////////////////////////////////////////////////////
$router->get('/api/companies', function () {
    (new HomeController)->allCompanies();
});
$router->get('/api/fivecompanies', function () {
    (new HomeController)->fiveCompanies();
});
$router->get('/api/companies/(\d+)', function ($id) {
    (new HomeController)->showCompany($id);
});

// INVOICES /////////////////////////////////////////////////////////////////
$router->get('/api/invoices', function () {
    (new HomeController)->allInvoices();
});
$router->get('/api/fiveinvoices', function () {
    (new HomeController)->fiveInvoices();
});
$router->get('/api/invoices/(\d+)', function ($id) {
    (new HomeController)->showInvoice($id);
});

// CONTACTS /////////////////////////////////////////////////////////////////
$router->get('/api/contacts', function () {
    (new HomeController)->allContacts();
});
$router->get('/api/fivecontacts', function () {
    (new HomeController)->fiveContacts();
});
$router->get('/api/contacts/(\d+)', function ($id) {
    (new HomeController)->showContact($id);
});

// POST METHOD  ////////////////////////////////////////////////////////////////
// CONTACT  /////////////////////////////////
$router->post('/api/add-contact', function () {
    (new HomeController)->createContactWithCompany();
});
$router->run();
