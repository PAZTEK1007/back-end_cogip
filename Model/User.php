<?php

namespace App\Model;

use App\Model\BaseModel;
use App\Model\Error;
use PDO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
    public function getUserByEmail($email)
    {
        // Recherche de l'utilisateur dans la base de données
        $query = $this->connection->prepare(
            "SELECT email, password FROM users WHERE email = :email"        );

        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            $statusCode = 404;
            $status = 'error';
        } else {
            $statusCode = 200;
            $status = 'success';
        }

        $response =
        [
            'message' =>  'User by email',
            'code' => $statusCode,
            'content-type' => 'application/json',
            'status' => $status,
            'data' => $user,
        ];

        $jsonData = json_encode($response, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        http_response_code($statusCode);

        return $jsonData;
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
    // CREATE USER  ////////////////////////////////////////////////////////////////////////////////////////////

    public function createUser($firstName, $lastName, $email, $password)
    {
        try {
            // Insérer dans la table users
            $query = $this->connection->prepare(
                "INSERT INTO users (first_name, last_name, email, password, role_id, created_at, updated_at)
                VALUES (:first_name, :last_name, :email, :password, 2, NOW(), NOW())"
            );

            $query->bindParam(':first_name', $firstName);
            $query->bindParam(':last_name', $lastName);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            return $query->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // DELETE USER BY ID ////////////////////////////////////////////////////////////////////////////////////////////

    public function delete($id){
        $query = $this->connection->prepare(
            "DELETE FROM users WHERE id = :id"
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