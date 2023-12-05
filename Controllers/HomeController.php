<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Queries\Database;
use App\Model\User;
use PDO;

class HomeController extends Controller
{

    /*
    * return view
    */
    public function index()
    {
        $users = $this->getUsers();

        // Passez les données à la vue
        return $this->view('welcome', ["name" => "Cogip"]);
    }
}
