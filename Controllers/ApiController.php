<?php

namespace App\Controllers;

use App\Queries\Database;
use App\Model\User;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use PDO;
use PDOException;

class ApiController {

    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }
    
    public function index()
    {
        $users = $this->fetchUsers();

        return new JsonResponse(['users' => $users]);
    }

    public function fetchUsers()
    {
        try {

            $connection = $this->database->getConnection();

            $query = $connection->prepare("SELECT * FROM users");
            $query->execute();
            $usersData = $query->fetchAll(PDO::FETCH_ASSOC);
    
            $users = [];
            foreach ($usersData as $userData) 
            {
                $users[] = new User(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['role_id'],
                    $userData['last_name'],
                    $userData['email'],
                    $userData['password'],
                    $userData['created_at'],
                    $userData['updated_at']
                );
            }
            var_dump($users);
            return $users;
        } 
        catch (PDOException $e) 
        {
            return new JsonResponse(['error' => 'Erreur de la base de données'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
