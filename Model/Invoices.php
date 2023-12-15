<?php

namespace App\Model;

use App\Model\BaseModel;
use PDO;
use Exception;

class Invoices extends BaseModel
{
    // GET ALL INVOICES //////////////////////////////////////////////////////////////////////////
    public function getAllInvoices()
    {
        $query = $this->connection->prepare(
            "SELECT invoices.id, invoices.ref, invoices.date_due, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company
         ORDER BY invoices.created_at DESC"
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
                'message' => 'List of all invoices',
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




    //GET FIRST FIVE COMPANIES ///////////////////////////////////////////////////////////////////////////
    public function getFirstFiveInvoices()
    {
        $query = $this->connection->prepare(
            "SELECT invoices.id, invoices.ref, invoices.date_due, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company
         ORDER BY invoices.created_at DESC
         LIMIT 5 OFFSET 0"
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
                'message' => 'List of 5 invoices',
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



    // GET INVOICE BY ID ///////////////////////////////////////////////////////////////////////////////////////////////
    public function show($invoiceId)
    {
        $invoiceDetails = $this->getInvoiceById($invoiceId);

        $jsonData = json_encode($invoiceDetails, JSON_PRETTY_PRINT);
        // Vérifier si la compagnie a été trouvée
        if (!$invoiceDetails) {
            $message = 'Invoice not found';
            $statusCode = 404;
            $status = 'error';
        } else {
            $message = 'Invoice details';
            $statusCode = 200;
            $status = 'success';
        }
        // Retourner une réponse JSON avec un statut d'erreur
        $response = [
            'message' => $message,
            'content-type' => 'application/json',
            'status' => $status,
            'code' => $statusCode,
            'data' => $invoiceDetails,
        ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo $jsonData;
    }

    private function getInvoiceById($invoiceId)
    {
        $query = $this->connection->prepare(
            "SELECT invoices.id, invoices.ref, invoices.date_due, invoices.created_at AS invoice_creation, companies.name AS company_name
        FROM types 
         JOIN companies ON types.id = companies.type_id
         JOIN invoices ON companies.id = invoices.id_company
         WHERE invoices.id = :id"
        );
        $query->bindParam(':id', $invoiceId, PDO::PARAM_INT);
        $query->execute();
        $invoiceDetails = $query->fetchAll(PDO::FETCH_ASSOC);
        return $invoiceDetails;
    }


    // POST METHOD /////////////////////////////////////////////////////////////////////////////////
    public function createInvoice($ref, $id_company, $date_due)
    {
        try {
            $query = $this->connection->prepare(
                "INSERT INTO invoices (ref, id_company,date_due) VALUES (:ref, :id_company, :date_due)"
            );

            $query->bindParam(':ref', $ref);
            $query->bindParam(':id_company', $id_company);
            $query->bindParam(':date_due', $date_due);
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
    // DELETE INVOICE BY ID ////////////////////////////////////////////////////////////////////////////////////////////
    public function delete($id)
    {
        $query = $this->connection->prepare(
            "DELETE FROM invoices WHERE id = :id"
        );

        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $companiesid = $query->fetchAll(PDO::FETCH_ASSOC);

        // Convertir en JSON
        $jsonData = json_encode($companiesid, JSON_PRETTY_PRINT);

        if (empty($companiesid)) {
            $statusCode = 500;
            $status = 'error';
        } else {
            $statusCode = 200;
            $status = 'success';
        }

        $response =
            [
                'message' => 'List of invoices by id',
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


    //  UPDATE INVOICE //////////////////////////////////////////////////
    public function updateInvoice($id)
    {
        try {
            // Récupérer le corps de la requête JSON
            $body = file_get_contents('php://input');
            $data = json_decode($body);

            // Vérifier si le type existe déjà
            $invoiceId = $this->getInvoiceIdByName($data->ref);

            // Si le type existe déjà, retourner une erreur
            if ($invoiceId) {
                http_response_code(400);
                echo json_encode(['message' => 'Invoice already exists']);
                exit();
            }

            // Mettre à jour le type
            $query = $this->connection->prepare(
                "UPDATE invoices SET ref = :ref, id_company = :id_company, date_due = :date_due, created_at = :created_at, updated_at = :updated_at WHERE id = :id"
            );

            $query->bindParam(':ref', $data->ref);
            $query->bindParam(':id_company', $data->id_company);
            $query->bindParam(':cate_due', $data->date_due);
            $query->bindParam(':created_at', $data->created_at);
            $query->bindParam(':updated_at', $data->updated_at);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
