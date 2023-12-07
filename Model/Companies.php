<?php

namespace App\Model;

use App\Queries\Database;
use PDO;

class Companies
{
    public function getAllCompanies()
    {
        // Utilisez la classe Database pour obtenir une connexion
        $database = Database::getInstance();
        $connection = $database->getConnection();
        $query = $connection->prepare(
            "SELECT types.name AS type_name,companies.name AS company_name, companies.country, companies.tva, companies.created_at AS company_creation, contacts.name, contacts.email, contacts.phone, contacts.created_at AS user_creation, invoices.ref, invoices.created_at AS invoice_creation
        FROM types 
        JOIN companies ON types.id = companies.type_id
        JOIN contacts ON companies.id = contacts.company_id
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



    //GET FIRST FIVE COMPANIES
    public function firstFiveCompanies()
    {
        // Utilisez la classe Database pour obtenir une connexion
        $database = Database::getInstance();
        $connection = $database->getConnection();
        $query = $connection->prepare(
            "SELECT types.name AS type_name,companies.name AS company_name, companies.country, companies.tva, companies.created_at AS company_creation, contacts.name, contacts.email, contacts.phone, contacts.created_at AS user_creation, invoices.ref, invoices.created_at AS invoice_creation
         FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN contacts ON companies.id = contacts.company_id
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
}
