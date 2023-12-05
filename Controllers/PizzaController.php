<?php

namespace App\Controllers;

use App\Core\Controller;

class PizzaController extends Controller
{
    /*
    * return view
    */
    public function index()
    {
        return $this->view('welcome',["name" => "pizza"]);
    }
}