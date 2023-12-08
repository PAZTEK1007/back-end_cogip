<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\User;
use App\Model\Companies;
use App\Model\Invoices;
use App\Model\Contacts;
use App\Model\Types;
use Exception;



class HomeController extends Controller
{
    private $userModel;
    private $companiesModel;
    private $invoicesModel;
    private $contactsModel;
    private $typesModel;

    public function __construct()
    {
        $this->userModel = new User;
        $this->companiesModel = new Companies;
        $this->invoicesModel = new Invoices;
        $this->contactsModel = new Contacts;
        $this->typesModel = new Types;
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

    public function createNewCompany()
    {
        try {
            // Récupérer le corps de la requête JSON
            $jsonBody = file_get_contents("php://input");
            // Transformer le JSON en un tableau PHP associatif
            $data = json_decode($jsonBody, true);

            $typeName = $data['type_name'];
            $companyName = $data['company_name'];
            $type_id = $data['type_id'];
            $country = $data['country'];
            $tva = $data['tva'];
            $companyCreated_at = $data['company_creation'];

            //vérifier si le type_name existe dans la db
            $typeId = $this->typesModel->getTypeIdByName($typeName);
            //vérifier si company_name existe déjà dans la db
            $companyId = $this->companiesModel->getCompanyIdByName($companyName);

            //si la company existe déjà -> message d'erreur
            if (!empty($companyId)) {
                http_response_code(400);
                echo json_encode(["message" => "La company existe deja."]);
                return;
            }
            // Ajouter l'id de type à type_id de companies
            $companyData['type_id'] = $typeId;

            // Créer l'entreprise en utilisant le modèle Companies
            $this->companiesModel->createCompany($typeId, $companyName, $type_id, $country, $tva, $companyCreated_at);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // POST CONTACT  //////////////////////////////////////////////
    public function createNewContact()
    {

        try {
            // Récupérer le corps de la requête JSON
            $jsonBody = file_get_contents("php://input");
            // Transformer le JSON en un tableau PHP associatif
            $data = json_decode($jsonBody, true);

            // Extraire les données nécessaires du tableau associatif
            $contactName = $data['name'];
            $email = $data['email'];
            $phone = $data['phone'];
            $contactCreated_at = $data['contact_creation'];
            $companyName = $data['company_name'];

            //vérifier si company_name existe déjà dans la db
            $companyId = $this->companiesModel->getCompanyIdByName($companyName);
            //vérifier si ref existe déjà ans la db
            $contactId = $this->contactsModel->getContactIdByName($contactName);

            //si l'entreprise n'existe pas ->message d'erreur
            if (!$companyId) {
                http_response_code(400);
                echo json_encode(["message" => "L'entreprise n'existe pas. Veuillez creer l'entreprise avant d'ajouter un contact."]);
                return;
            }

            //si le name existe déjà -> message d'erreur
            if (!empty($contactId)) {
                http_response_code(400);
                echo json_encode(["message" => "Le contact existe deja."]);
                return;
            }


            //ajouter l'id de la company à company_id de contact
            $contactData['company_id'] = $companyId;

            //créer le contact
            $this->contactsModel->createContact($contactName, $companyId, $email, $phone, $contactCreated_at);
        } catch (Exception $e) {
            throw $e;
        }
    }


    // POST INVOICE //////////////////////////////////////////////////

    public function createNewInvoice()
    {
        try {
            // Récupérer le corps de la requête JSON
            $jsonBody = file_get_contents("php://input");
            // Transformer le JSON en un tableau PHP associatif
            $data = json_decode($jsonBody, true);

            $ref = $data['ref'];
            $invoiceCreated_at = $data['invoice_creation'];
            $companyName = $data['company_name'];

            // Vérifier si company_name existe déjà dans la db
            $companyId = $this->companiesModel->getCompanyIdByName($companyName);
            //vérifier si ref existe déjà ans la db
            $invoiceId = $this->invoicesModel->getInvoiceIdByName($ref);

            // Si l'entreprise n'existe pas -> message d'erreur
            if (!$companyId) {
                http_response_code(400);
                echo json_encode(["message" => "L'entreprise n'existe pas. Veuillez creer l'entreprise avant d'ajouter une facture."]);
                return;
            }
            //si la ref existe déjà -> message d'erreur
            if (!empty($invoiceId)) {
                http_response_code(400);
                echo json_encode(["message" => "La facture existe deja."]);
                return;
            }

            // Ajouter l'id de la company à company_id de invoices
            $invoiceData['id_company'] = $companyId;

            $this->invoicesModel->createInvoice($ref, $companyId, $invoiceCreated_at);
        } catch (Exception $e) {
            throw $e;
        }
    }
}



//vérifier si la company existe erreur 404
//vérifier si tous les champs sont remplis sinon erreur 500 + précision