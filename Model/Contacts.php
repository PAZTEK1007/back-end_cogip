<?php

namespace App\Model;

use App\Model\BaseModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use PDO;
use Exception;

class Contacts extends BaseModel
{
    // GET METHOD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllContacts()
    {
        $query = $this->connection->prepare(
            "SELECT contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        // Convertir en JSON
        //JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.
        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);

        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        echo new JsonResponse(
            $jsonData,
            empty($companiesData) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
            [
                'content-type' => 'application/json',
                'status' => 'success'
            ],
            true
        );
    }


    public function getFirstFiveContacts()
    {
        $query = $this->connection->prepare(
            "SELECT contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
         LIMIT 5 OFFSET 0"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);


        echo new JsonResponse(
            $jsonData,
            empty($companiesData) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
            [
                'content-type' => 'application/json',
                'status' => 'success'
            ],
            true
        );
    }

    public function show($id)
    {
        $query = $this->connection->prepare(
            "SELECT contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
         WHERE contacts.id = :id"
        );
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);

        $companiesData = json_encode($companiesid, JSON_PRETTY_PRINT);
        echo new JsonResponse(
            $companiesData,
            empty($companiesid) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
            [
                'content-type' => 'application/json',
                'status' => 'success'
            ],
            true
        );
    }

    // POST METHOD  //////////////////////////////////////////////////////////////////////////////////////////////
    public function createContact($contactName, $company_id, $email, $phone, $contactCreated_at)
    {
        try {
            $query = $this->connection->prepare(
                "INSERT INTO contacts (name, company_id, email, phone, created_at, updated_at) VALUES (:name, :company_id, :email, :phone, :created_at, :updated_at)"
            );

            $query->bindParam(':name', $contactName);
            $query->bindParam(':company_id', $company_id);
            $query->bindParam(':email', $email);
            $query->bindParam(':phone', $phone);
            $query->bindParam(':created_at', $contactCreated_at);
            $query->bindParam(':updated_at', $contactCreated_at);
            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    //vérifier si le contact existe déjà et récupérer son ID si c'est le cas
    public function getContactIdByName($contactName)
    {
        $query = $this->connection->prepare("SELECT id FROM contacts WHERE name= :contactName");
        $query->bindParam(':contactName', $contactName);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        //retourner l'ID du contact si une correspondance sinon retourner null
        return $result ? $result['id'] : null;
    }
}
