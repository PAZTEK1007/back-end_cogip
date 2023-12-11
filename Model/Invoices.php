<?php

namespace App\Model;

use App\Model\BaseModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use PDO;
use Exception;

class Invoices extends BaseModel
{
    // GET ALL INVOICES //////////////////////////////////////////////////////////////////////////
    public function getAllInvoices()
    {
        $query = $this->connection->prepare(
            "SELECT invoices.ref, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company"
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
            ['content-type' => 'application/json','status' => 'success', 'Access-Control-Allow-Origin' => '*'],
            true
        );
    }



    //GET FIRST FIVE COMPANIES ///////////////////////////////////////////////////////////////////////////
    public function getFirstFiveInvoices()
    {
        $query = $this->connection->prepare(
            "SELECT invoices.ref, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company
         LIMIT 5 OFFSET 0"
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
            ['content-type' => 'application/json','status' => 'success', 'Access-Control-Allow-Origin' => '*'],
            true
        );
    }


    // GET INVOICE BY ID ///////////////////////////////////////////////////////////////////////////////////////////////
    public function show($id)
    {
        $query = $this->connection->prepare(
            "SELECT invoices.ref, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company
         WHERE invoices.id = :id"
        );
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Convertir en JSON 
        $companiesData = json_encode($companiesid, JSON_PRETTY_PRINT);
        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        echo new JsonResponse(
            $companiesData,
            empty($companiesid) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK,
            ['content-type' => 'application/json','status' => 'success', 'Access-Control-Allow-Origin' => '*'],
            true
        );
    }

    // POST METHOD /////////////////////////////////////////////////////////////////////////////////
    public function createInvoice($ref, $id_company, $invoiceCreated_at)
    {
        try {
            $query = $this->connection->prepare(
                "INSERT INTO invoices (ref, id_company, created_at, updated_at) VALUES (:ref, :id_company, :created_at, :updated_at)"
            );

            $query->bindParam(':ref', $ref);
            $query->bindParam(':id_company', $id_company);
            $query->bindParam(':created_at', $invoiceCreated_at);
            $query->bindParam(':updated_at', $invoiceCreated_at);
            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    //vérifier si l'invoice existe déjà et récupérer son ID si c'est le cas
    public function getInvoiceIdByName($ref)
    {
        $query = $this->connection->prepare("SELECT id FROM invoices WHERE ref = :ref");
        $query->bindParam(':ref', $ref);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        //retourner l'ID de l'invoice si une correspondance sinon retourner null
        return $result ? $result['id'] : null;
    }
}
