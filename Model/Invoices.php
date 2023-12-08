<?php

namespace App\Model;

use App\Model\BaseModel;
use PDO;

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
        header('Content-Type: application/json');
        echo $jsonData;
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
        header('Content-Type: application/json');
        echo $jsonData;
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
        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        header('Content-Type: application/json');
        echo json_encode($companiesid, JSON_PRETTY_PRINT);
    }
}
