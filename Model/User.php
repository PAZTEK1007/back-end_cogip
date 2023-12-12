<?php

namespace App\Model;

use App\Model\BaseModel;
use App\Model\Error;
use PDO;

class User extends BaseModel
{

    ////////GET ALL USERS//////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllUsers()
    {

        //requête pour récupérer tous les users
        $query = $this->connection->prepare(
            "SELECT roles.name AS role_name, users.first_name, users.last_name, users.email, users.password
            FROM roles
            JOIN users ON roles.id = users.role_id
            JOIN roles_permission ON roles.id = roles_permission.role_id
            JOIN permissions ON roles_permission.permission_id = permissions.id"
        );

        $query->execute();
        $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

        // Convertir en JSON
        // JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.

        $jsonData = json_encode($usersData, JSON_PRETTY_PRINT);

        if (empty($usersData)) 
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
            'message' => 'List of all users',
            'code' => $statusCode,
            'content-type' => 'application/json',
            'status' => $status,
            'data' => $usersData,
        ];
    
        $jsonData = json_encode($response, JSON_PRETTY_PRINT);
    
        header('Content-Type: application/json');
        http_response_code($statusCode);
    
        echo $jsonData;
    }



    //////GET FIRST FIVE USERS/////////////////////////////////////////////////////////////////////////////////////////

    public function getFirstFiveUsers()
    {
        $query = $this->connection->prepare(
            "SELECT roles.name AS role_name, users.first_name, users.last_name, users.email, users.password
                    FROM roles
                    JOIN users ON roles.id = users.role_id
                    JOIN roles_permission ON roles.id = roles_permission.role_id
                    JOIN permissions ON roles_permission.permission_id = permissions.id
                    LIMIT 5 OFFSET 0"
        );

        $query->execute();
        $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

        // Convertir en JSON
        // JSON_PRETTY_PRINT -> meilleure lisibilité lors de l'affichage.
        $jsonData = json_encode($usersData, JSON_PRETTY_PRINT);

        if (empty($usersData))
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
            'message' => 'List of 5 users',
            'code' => $statusCode,
            'content-type' => 'application/json',
            'status' => $status,
            'data' => $usersData,
        ];
    
        $jsonData = json_encode($response, JSON_PRETTY_PRINT);
    
        header('Content-Type: application/json');
        http_response_code($statusCode);
    
        echo $jsonData;
    }


    // GET USER BY ID  ////////////////////////////////////////////////////////////////////////////////////////////
    public function show($id)
    {
        $query = $this->connection->prepare(
            "SELECT roles.name AS role_name, users.first_name, users.last_name, users.email, users.password
                    FROM roles
                    JOIN users ON roles.id = users.role_id
                    JOIN roles_permission ON roles.id = roles_permission.role_id
                    JOIN permissions ON roles_permission.permission_id = permissions.id
                    WHERE users.id = :id"
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
            'message' => 'users',
            'code' => $statusCode,
            'content-type' => 'application/json',
            'status' => $status,
            'data' => $companiesid,
        ];
    
        $jsonData = json_encode($response, JSON_PRETTY_PRINT);
    
        header('Content-Type: application/json');
        http_response_code($statusCode);
    
        echo $jsonData;
    }
}
