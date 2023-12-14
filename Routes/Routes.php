<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;
use App\Model\Auth;


$auth = new Auth($_ENV["SECRET_KEY"]);
$router = new Router();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}


$router->mount('/api', function () use ($router, $auth) {
    // GET METHOD  //////////////////////////////////////////////////////
    $router->get('/login', function () use ($auth) {
        try {
            $token = $auth->authenticate("admin", "motdepasse");
            $decodedToken = $auth->verifyToken($token);

            if ($decodedToken) {
                echo "Token valide. Données du token : \n";
                print_r($decodedToken);
            } else {
                echo "Token invalide.\n";
            }
        } catch (\Exception $e) {
            echo "Erreur d'authentification : " . $e->getMessage();
        }
        
        $token = $auth->authenticate("admin", "motdepasse");
        $decodedToken = $auth->verifyToken($token);
        
            if ($decodedToken) {
                echo "Token valide. Données du token : \n";
                print_r($decodedToken);
            } else {
                echo "Token invalide.\n";
            }
       
    });
    
    $router->before('GET', '/users', function () use ($auth) {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    
        if (!$auth->verifyToken($token)) {
            echo "Accès non autorisé. Token invalide.";
            exit;
        }
    });
    
    // USERS /////////////////////////////////////////////////////////////////
    $router->get('/users', function () {
        (new HomeController())->allUsers();
    });
    $router->get('/fiveusers', function () {
        (new HomeController())->fiveUsers();
    });
    $router->get('/users/(\d+)', function ($id) {
        (new HomeController())->showUser($id);
    });

    // COMPANIES /////////////////////////////////////////////////////////////////
    $router->get('/companies', function () {
        (new HomeController())->allCompanies();
    });
    $router->get('/fivecompanies', function () {
        (new HomeController())->fiveCompanies();
    });
    $router->get('/companies/(\d+)', function ($id) {
        (new HomeController())->showCompany($id);
    });

    // INVOICES /////////////////////////////////////////////////////////////////
    $router->get('/invoices', function () {
        (new HomeController())->allInvoices();
    });
    $router->get('/fiveinvoices', function () {
        (new HomeController())->fiveInvoices();
    });
    $router->get('/invoices/(\d+)', function ($id) {
        (new HomeController())->showInvoice($id);
    });

    // CONTACTS /////////////////////////////////////////////////////////////////
    $router->get('/contacts', function () {
        (new HomeController())->allContacts();
    });
    $router->get('/fivecontacts', function () {
        (new HomeController())->fiveContacts();
    });
    $router->get('/contacts/(\d+)', function ($id) {
        (new HomeController())->showContact($id);
    });

    // POST METHOD  ////////////////////////////////////////////////////////////////

    // COMPANY  /////////////////////////////////
    $router->post('/add-company', function () {
        (new HomeController())->createNewCompany();
    });

    // CONTACT /////////////////////////////////////////
    $router->post('/add-contact', function () {
        (new HomeController())->createNewContact();
    });

    // INVOICE ////////////////////////////////////////////
    $router->post('/add-invoice', function () {
        (new HomeController())->createNewInvoice();
    });

    // DELETE METHOD  ////////////////////////////////////////////////////////////////

    // USER /////////////////////////////////////////////////////////////////////
    $router->delete('/del-user/(\d+)', function ($id) {
        (new HomeController())->delUser($id);
    });
    // COMPANY /////////////////////////////////////////////////////////////////
    $router->delete('/del-company/(\d+)', function ($id) {
        (new HomeController())->delCompany($id);
    });
    // INVOICE /////////////////////////////////////////////////////////////////
    $router->delete('/del-invoice/(\d+)', function ($id) {
        (new HomeController())->delInvoice($id);
    });
    // CONTACT /////////////////////////////////////////////////////////////////
    $router->delete('/del-contact/(\d+)', function ($id) {
        (new HomeController())->delContact($id);
    });

    // PUT METHOD  ////////////////////////////////////////////////////////////////
    // COMPANY /////////////////////////////////////////////////////////////////
    $router->put('/update-company/(\d+)', function ($id) {
        (new HomeController())->updateCompany($id);
    });
    // INVOICE /////////////////////////////////////////////////////////////////
    $router->put('/update-invoice/(\d+)', function ($id) {
        (new HomeController())->updateInvoice($id);
    });
    // CONTACT /////////////////////////////////////////////////////////////////
    $router->put('/update-contact/(\d+)', function ($id) {
        (new HomeController())->updateContact($id);
    });

});

$router->run();
