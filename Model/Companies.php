<?php

namespace App\Model;

use App\Model\BaseModel;
use PDO;
use Exception;

class Companies extends BaseModel
{

    // GET ALL COMPANIES   ////////////////////////////////////////////////////////////////////
    public function getAllCompanies()
    {
        $query = $this->connection->prepare(
            "SELECT types.name AS type_name,companies.name AS company_name, companies.country, companies.tva, companies.created_at AS company_creation
        FROM types 
        JOIN companies ON types.id = companies.type_id"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        // Convertir en JSON
        //JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.
        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);

        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        if (empty($companiesData))
        {
            $statusCode = 500;
            $status = 'error';
        }
        else 
        {
            $statusCode = 200;
            $status = 'success';
        }
    
        $response = 
        [
            'message' => 'List of all companies',
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


    // GET FIRST FIVE COMPANIES //////////////////////////////////////////////////////////////////////
    public function getFirstFiveCompanies()
    {
        $query = $this->connection->prepare(
            "SELECT types.name AS type_name,companies.name AS company_name, companies.country, companies.tva, companies.created_at AS company_creation
         FROM types 
         JOIN companies ON types.id = companies.type_id
         LIMIT 5 OFFSET 0"
        );
        $query->execute();
        $companiesData = $query->fetchAll(PDO::FETCH_ASSOC);


        // Convertir en JSON
        //JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.
        $jsonData = json_encode($companiesData, JSON_PRETTY_PRINT);

        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        if (empty($companiesData))
        {
            $statusCode = 500;
            $status = 'error';
        }
        else 
        {
            $statusCode = 200;
            $status = 'success';
        }
    
        $response = 
        [
            'message' => 'List of 5 companies',
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


    // GET COMPANY BY ID ///////////////////////////////////////////////////////////////
    public function show($id)
    {
        $query = $this->connection->prepare(
            "SELECT types.name AS type_name,companies.name AS company_name, companies.country, companies.tva, companies.created_at AS company_creation
         FROM types 
         JOIN companies ON types.id = companies.type_id
         WHERE companies.id = :id"
        );
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);
        // Convertir en JSON
        $companiesData = json_encode($companiesid, JSON_PRETTY_PRINT);

        if (empty($companiesid))
        {
            $statusCode = 500;
            $status = 'error';
        }
        else 
        {
            $statusCode = 200;
            $status = 'success';
        }
    
        $response = 
        [
            'message' => 'List of companies by id',
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

    // POST NEW COMPANY  ////////////////////////////////////////////////////////////////
    public function createCompany($companyName, $type_id, $country, $tva, $companyCreated_at)
    {
        try {
            // Insérer dans la table Companies
            $query = $this->connection->prepare("INSERT INTO companies (name, type_id, country, tva, created_at, updated_at) VALUES (:name, :type_id, :country, :tva, :created_at, :updated_at)");

            $query->bindParam(':name', $companyName);
            $query->bindParam(':type_id', $type_id);
            $query->bindParam(':country', $country);
            $query->bindParam(':tva', $tva);
            $query->bindParam(':created_at', $companyCreated_at);
            $query->bindParam(':updated_at', $companyCreated_at);
            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    //vérifier si la company existe déjà et récupérer son ID si c'est le cas
    public function getCompanyIdByName($companyName)
    {
        $query = $this->connection->prepare("SELECT id FROM companies WHERE name = :companyName");
        $query->bindParam(':companyName', $companyName);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        //retourner l'ID de l'entreprise si une correspondance sinon retourner null
        return $result ? $result['id'] : null;
    }
    // DELETE COMPANY BY ID ////////////////////////////////////////////////////////////////////////////////////////////
    public function delete($id){
        $query = $this->connection->prepare(
            "DELETE FROM companies WHERE id = :id"
        );
    
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);

        // Convertir en JSON
        $jsonData = json_encode($companiesid, JSON_PRETTY_PRINT);

        if (empty($companiesid)) 
        {
            $statusCode = 500;
            $status = 'error';
        } 
        else 
        {
            $statusCode = 200;
            $status = 'success';
        }
    
        $response = 
        [
            'message' => 'List of companies by id',
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

            // Vérifier si la company existe déjà
            $companyId = $this->getCompanyIdByName($data->name);

            // Si la company existe déjà, retourner un code d'erreur
            if ($companyId) {
                http_response_code(409);
                echo json_encode(['message' => 'Company already exists']);
                exit();
            }

            // Mettre à jour la company
            $query = $this->connection->prepare("UPDATE companies SET name = :name, type_id = :type_id, country = :country, tva = :tva, updated_at = :updated_at WHERE id = :id");

            $query->bindParam(':name', $data->name);
            $query->bindParam(':type_id', $data->type_id);
            $query->bindParam(':country', $data->country);
            $query->bindParam(':tva', $data->tva);
            $query->bindParam(':updated_at', $data->updated_at);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
