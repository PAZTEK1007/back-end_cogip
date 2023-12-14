<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\User;
use App\Model\Companies;
use App\Model\Invoices;
use App\Model\Contacts;
use App\Model\Types;
// use App\Model\Validator;
// use InvalidArgumentException;
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
    // DELETE USER   ////////////////////////////////////////////////////////
    public function delUser($id)
    {
        $this->userModel->delete($id);
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

    public function showCompany($companyId)
    {
        $this->companiesModel->show($companyId);
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
            $country = $data['country'];
            $tva = $data['tva'];

            //valider et sanitiser ici
            // Validator::validateAndSanitize($companyName, 3, 50, 'name');
            // Validator::validateAndSanitize($country, $tva, 'tva');
            // Validator::validateAndSanitize($companyCreated_at, 'date');

            // vérifier si le type_name existe dans la db
            $typeId = $this->typesModel->getTypeIdByName($typeName);

            // vérifier si company_name existe déjà dans la db
            $companyId = $this->companiesModel->getCompanyIdByName($companyName);

            //si la company existe déjà -> message d'erreur
            if (!empty($companyId)) {
                http_response_code(400);
                echo json_encode(["message" => "La company existe deja."]);
                return;
            }

            // Créer l'entreprise avec le numéro de TVA
            // Validator::validateTva($country, $tva);


            // Créer l'entreprise 
            $company = $this->companiesModel->createCompany($companyName, $typeId, $country, $tva);
            $response =
                [
                    'data' => $company,
                    'status' => 200,
                    'message' => 'La company a été créée avec succès.',
                ];

            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT);
            // } catch (InvalidArgumentException $e) {
            //     // Gérer l'exception d'erreur de validation
            //     http_response_code(400);
            //     echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur s'est produite lors de la creation de la company."], JSON_PRETTY_PRINT);
        }
    }

    // DELETE COMPANY   ////////////////////////////////////////////////////////
    public function delCompany($id)
    {
        $this->companiesModel->delete($id);
    }

    // UPDATE COMPANY  ////////////////////////////////////////////////////////
    public function updateCompany($id)
    {
        $this->companiesModel->update($id);
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

    // POST INVOICE //////////////////////////////////////////////////
    public function createNewInvoice()
    {
        try {
            // Récupérer le corps de la requête JSON
            $jsonBody = file_get_contents("php://input");
            // Transformer le JSON en un tableau PHP associatif
            $data = json_decode($jsonBody, true);

            $ref = $data['ref'];
            $date_due = $data['date_due'];
            $companyName = $data['company_name'];

            //valider et sanitiser
            // Validator::validateAndSanitize($date_due, 'date');


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

            $invoice = $this->invoicesModel->createInvoice($ref, $companyId, $date_due);

            $response =
                [
                    'data' => $invoice,
                    'status' => 200,
                    'message' => 'La facture a été créée avec succès.',
                ];

            header('Content-Type: application/json');

            echo json_encode($response, JSON_PRETTY_PRINT);
            // } catch (InvalidArgumentException $e) {
            //     // Gérer l'exception d'erreur de validation
            //     http_response_code(400);
            //     echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur s'est produite lors de la creation de la facture."], JSON_PRETTY_PRINT);
        }
    }

    // DELETE INVOICE   ////////////////////////////////////////////////////////
    public function delInvoice($id)
    {
        $this->invoicesModel->delete($id);
    }

    // UPDATE INVOICE  ////////////////////////////////////////////////////////
    public function updateInvoice($id)
    {
        $this->invoicesModel->updateInvoice($id);
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
            $companyName = $data['company_name'];

            //valider et sanitiser
            // Validator::validateAndSanitize($contactName, 3, 50, 'name');
            // Validator::validateAndSanitize($email, 'email');
            // Validator::validateAndSanitize($phone, 'phone');
            // Validator::validateAndSanitize($contactCreated_at, 'date');

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
            $contact = $this->contactsModel->createContact($contactName, $companyId, $email, $phone);
            $response =
                [
                    'data' => $contact,
                    'status' => 200,
                    'message' => 'Le contact a été créée avec succès.',
                ];

            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT);
            // } catch (InvalidArgumentException $e) {
            //     // Gérer l'exception d'erreur de validation
            //     http_response_code(400);
            //     echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur s'est produite lors de la creation du contact."], JSON_PRETTY_PRINT);
        }
    }

    // DELETE CONTACT   ////////////////////////////////////////////////////////
    public function delContact($id)
    {
        $this->contactsModel->delete($id);
    }

    // UPDATE CONTACT  ////////////////////////////////////////////////////////
    public function updateContact($id)
    {
        $this->contactsModel->update($id);
    }
}
