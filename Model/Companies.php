<?php

namespace App\Model;

use App\Model\BaseModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // echo new JsonResponse(
        //     $jsonData,
        //     empty($companiesData) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
        //     [
        //         'content-type' => 'application/json',
        //         'status' => 'success',
        //         'Access-Control-Allow-Origin' => '*',
        //         'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        //         'Access-Control-Allow-Credentials' => 'false'

        //     ],
        //     true
        // );
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
        // echo new JsonResponse(
        //     $jsonData,
        //     empty($companiesData) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
        //     [
        //         'content-type' => 'application/json',
        //         'status' => 'success',
        //         'Access-Control-Allow-Origin' => '*',
        //         'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        //         'Access-Control-Allow-Credentials' => 'false'

        //     ],
        //     true
        // );
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
        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        // echo new JsonResponse(
        //     $companiesData,
        //     empty($companiesid) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
        //     [
        //         'content-type' => 'application/json',
        //         'status' => 'success',
        //         'Access-Control-Allow-Origin' => '*',
        //         'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        //         'Access-Control-Allow-Credentials' => 'false'

        //     ],
        //     true
        // );
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
}
