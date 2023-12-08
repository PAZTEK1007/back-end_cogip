<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\User;
use App\Model\Companies;
use App\Model\Invoices;
use App\Model\Contacts;
use Exception;



class HomeController extends Controller
{
    private $userModel;
    private $companiesModel;
    private $invoicesModel;
    private $contactsModel;

    public function __construct()
    {
        $this->userModel = new User;
        $this->companiesModel = new Companies;
        $this->invoicesModel = new Invoices;
        $this->contactsModel = new Contacts;
    }

    //  GET USERS   ////////////////////////////////////////////////////////
    public function allUsers()
    {
        $this->userModel->getAllUsers();
    }

    public function fiveUsers()
    {
        $this->userModel->getFirstFiveUsers();
    }

    public function showUser($id)
    {
        $this->userModel->show($id);
    }


    // GET COMPANIES   ///////////////////////////////////////////////////////////
    public function allCompanies()
    {
        $this->companiesModel->getAllCompanies();
    }

    public function fiveCompanies()
    {
        $this->companiesModel->getFirstFiveCompanies();
    }

    public function showCompany($id)
    {
        $this->companiesModel->show($id);
    }


    //  GET INVOICES  ////////////////////////////////

    public function allInvoices()
    {
        $this->invoicesModel->getAllInvoices();
    }
    public function fiveInvoices()
    {
        $this->invoicesModel->getFirstFiveInvoices();
    }

    public function showInvoice($id)
    {
        $this->invoicesModel->show($id);
    }

    // GET CONTACTS   ///////////////////////////////////////////////
    public function allContacts()
    {
        $this->contactsModel->getAllContacts();
    }

    public function fiveContacts()
    {
        $this->contactsModel->getFirstFiveContacts();
    }

    public function showContact($id)
    {
        $this->contactsModel->show($id);
    }


    // POST COMPANY   ///////////////////////////////////////



    // POST INVOICE //////////////////////////////////////////////////



    // POST CONTACT  //////////////////////////////////////////////
    // dans le form il ya  les données de contacts mais le name de company du coup appel à 2 méthode 1 pour créer une company et une autre pour créer un contact
    // on lie les deux via l'id 
    public function createContactWithCompany()
    {
        try {
            // Récupérer le corps de la requête JSON
            $jsonBody = file_get_contents("php://input");

            // Transformer le JSON en un tableau PHP associatif
            $data = json_decode($jsonBody, true);

            // Extraire les données nécessaires du tableau associatif
            $contactName = $data['contactName'];
            $company_id = $data['company_id'];
            $email = $data['email'];
            $phone = $data['phone'];
            $nameCreated_at = $data['nameCreated_at'];
            $companyName = $data['companyName'];
            $type_id = $data['type_id'];
            $country = $data['country'];
            $tva = $data['tva'];
            $created_at = $data['created_at'];

            // Créer l'entreprise en utilisant le modèle Companies
            $companyId = $this->companiesModel->createCompany($companyName, $type_id, $country, $tva, $created_at);

            // Ajouter l'ID de l'entreprise aux données du contact
            $contactData['company_id'] = $companyId;

            // Créer le contact en utilisant le modèle Contacts
            $this->contactsModel->createContact($contactName, $company_id, $email, $phone, $nameCreated_at);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
