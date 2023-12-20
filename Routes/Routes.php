<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\HomeController;
use App\Model\Auth;


$auth = new Auth($_ENV["SECRET_KEY"]);
$router = new Router();

if (isset($_SERVER['HTTP_ORIGIN'])) 
{
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
    header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token, Access-Control-Allow-Headers, Access-Control-Request-Method, Access-Control-Request-Headers');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// MIDDLEWARE  /////////////////////////////////////////////////////////////
$router->before('POST', '/api/(.*)', function ($route) use ($auth) 
{   
    // Récupère le token
    $authorizationHeader = apache_request_headers()['Authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorizationHeader);

    // Vérifie le token
    $auth->verifyToken($token);

    if($auth->verifyToken($token) === true)
    {
        http_response_code(401);
        echo json_encode(['message' => 'Accès autorisé']);
        return;
    }
    else{
        http_response_code(401);
        echo json_encode(['message' => 'Accès non autorisé']);
        return;
    }
});
$router->before('DEL', '/api/(.*)', function ($route) use ($auth) 
{   
    // Récupère le token
    $authorizationHeader = apache_request_headers()['Authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorizationHeader);

    // Vérifie le token
    $auth->verifyToken($token);

    if($auth->verifyToken($token) === true)
    {
        http_response_code(401);
        echo json_encode(['message' => 'Accès autorisé']);
        return;
    }
    else{
        http_response_code(401);
        echo json_encode(['message' => 'Accès non autorisé']);
        return;
    }
});

$router->before('PUT', '/api/(.*)', function ($route) use ($auth) 
{   
    // Récupère le token
    $authorizationHeader = apache_request_headers()['Authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorizationHeader);

    // Vérifie le token
    $auth->verifyToken($token);

    if($auth->verifyToken($token) === true)
    {
        http_response_code(401);
        echo json_encode(['message' => 'Accès autorisé']);
        return;
    }
    else{
        http_response_code(401);
        echo json_encode(['message' => 'Accès non autorisé']);
        return;
    }
});
// ROUTES /////////////////////////////////////////////////////////////////////

$router->mount('/api', function () use ($router, $auth) 
{
    // LOGIN /////////////////////////////////////////////////////////////////
    $router->post('/login', function () use ($auth) 
    {
        // Récupération du body de la requête
        $jsonBody = file_get_contents("php://input");
        $data = json_decode($jsonBody, true);
        
        // Récupération des données
        $email = $data['email'];
        $password = $data['password'];

        // Vérification des données
        if (empty($email) || empty($password)) 
        {
            http_response_code(400);
            echo json_encode(['message' => 'Email et mot de passe requis']);
            return;
        }

        // Appel de la méthode authenticate
        $auth->authenticate($email, $password);

    });

    // GET METHOD  //////////////////////////////////////////////////////

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

    // USER ////////////////////////////////////////////
    $router->post('/register', function () {
        (new HomeController())->createNewUser();
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
