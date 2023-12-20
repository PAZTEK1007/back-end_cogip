<?php

namespace App\Model;

use App\Model\BaseModel;
use PDO;
use Exception;

class Contacts extends BaseModel
{
    // GET METHOD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllContacts()
    {
        $query = $this->connection->prepare(
            "SELECT contacts.id, contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
         ORDER BY contacts.name ASC"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        // Convertir en JSON
        //JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.
        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);

        if (empty($companiesData)) {
            $statusCode = 500;
            $status = 'error';
        } else {
            $statusCode = 200;
            $status = 'success';
        }

        $response =
            [
                'message' => 'List of all contacts',
                'content-type' => 'application/json',
                'code' => $statusCode,
                'status' => $status,
                'data' => $companiesData,
            ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo $jsonData;
    }



    public function getFirstFiveContacts()
    {
        $query = $this->connection->prepare(
            "SELECT contacts.id, contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
         ORDER BY contacts.created_at DESC
         LIMIT 5 OFFSET 0"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);

        if (empty($companiesData)) {
            $statusCode = 500;
            $status = 'error';
        } else {
            $statusCode = 200;
            $status = 'success';
        }

        $response =
            [
                'message' => 'List of 5 contacts',
                'content-type' => 'application/json',
                'code' => $statusCode,
                'status' => $status,
                'data' => $companiesData,
            ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo $jsonData;
    }


    public function show($contactId)
    {
        $contactDetails = $this->getContactById($contactId);

        $jsonData = json_encode($contactDetails, JSON_PRETTY_PRINT);
        // Vérifier si la compagnie a été trouvée
        if (!$contactDetails) {
            $message = 'Contact not found';
            $statusCode = 404;
            $status = 'error';
        } else {
            $message = 'Contact details';
            $statusCode = 200;
            $status = 'success';
        }
        // Retourner une réponse JSON avec un statut d'erreur
        $response = [
            'message' => $message,
            'content-type' => 'application/json',
            'status' => $status,
            'code' => $statusCode,
            'data' => $contactDetails,
        ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo $jsonData;
    }

    private function getContactById($contactId)
    {
        $query = $this->connection->prepare(
            "SELECT contacts.id, contacts.name, contacts.email, contacts.phone, contacts.created_at AS contact_creation, companies.name AS company_name
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
         WHERE contacts.id = :id"
        );
        $query->bindParam(':id', $contactId, PDO::PARAM_INT);
        $query->execute();
        $contactDetails = $query->fetchAll(PDO::FETCH_ASSOC);

        return $contactDetails;
    }


    // POST METHOD  //////////////////////////////////////////////////////////////////////////////////////////////
    public function createContact($contactName, $company_id, $email, $phone)
    {
        try {
            $query = $this->connection->prepare(
                "INSERT INTO contacts (name, company_id, email, phone) VALUES (:name, :company_id, :email, :phone)"
            );

            $query->bindParam(':name', $contactName);
            $query->bindParam(':company_id', $company_id);
            $query->bindParam(':email', $email);
            $query->bindParam(':phone', $phone);
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
    // DELETE CONTACT BY ID //////////////////////////////////////////////////////////////////////////////////////////////

    public function delete($id)
    {
        $query = $this->connection->prepare(
            "DELETE FROM contacts WHERE id = :id"
        );

        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);

        $companiesData = json_encode($companiesid, JSON_PRETTY_PRINT);

        if (empty($companiesid)) {
            $statusCode = 500;
            $status = 'error';
        } else {
            $statusCode = 200;
            $status = 'success';
        }

        $response =
            [
                'message' => 'List of contacts by id',
                'content-type' => 'application/json',
                'code' => $statusCode,
                'status' => $status,
                'data' => $companiesid,
            ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo $jsonData;
    }
    public function update($id)
    {
        try {
            // Récupérer le corps de la requête JSON
            $body = file_get_contents('php://input');
            $data = json_decode($body);

            // Vérifier si le contact existe déjà
            $contactId = $this->getContactIdByName($data->name);

            // Si le contact existe déjà, on retourne une erreur
            if ($contactId) {
                http_response_code(409);
                return json_encode(['message' => 'Contact already exists']);
            }

            // Mettre à jour le type
            $query = $this->connection->prepare(
                "UPDATE contacts SET name = :name , company_id = :company_id, email = :email, phone = :phone, created_at = :created_at, updated_at = :updated_at WHERE id = :id"
            );

            $query->bindParam(':name', $data->name);
            $query->bindParam(':company_id', $data->company_id);
            $query->bindParam(':email', $data->email);
            $query->bindParam(':phone', $data->phone);
            $query->bindParam(':created_at', $data->created_at);
            $query->bindParam(':updated_at', $data->updated_at);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
