<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\User;
use App\Model\Companies;



class HomeController extends Controller
{

    public function users()
    {
        $userModel = new User();
        $userModel->getAllUsers();
    }

    public function fiveUsers()
    {
        $userModel = new User();
        $userModel->getFirstFive();
    }

    public function AllCompanies()
    {
        $companiesModel = new Companies();
        $companiesModel->getAllCompanies();
    }

    public function fiveCompanies()
    {
        $companiesModel = new Companies();
        $companiesModel->firstFiveCompanies();
    }
}
