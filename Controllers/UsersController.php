<?php

namespace App\Controllers;

use App\Queries\Database;
use App\Model\User;
use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function getUsers()
    {
        try {
            $connection = $this->database->getConnection();

            $query = $connection->prepare("SELECT * FROM users");
            $query->execute();
            $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

            $users = [];
            foreach ($usersData as $userData) {
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

            return new JsonResponse($users);

        } catch (PDOException $e) {
            return $this->createErrorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser($id)
    {
        try {
            $id = intval($id);

            $connection = $this->database->getConnection();

            $query = $connection->prepare("SELECT * FROM users WHERE id = :id");
            $query->execute(['id' => $id]);
            $userData = $query->fetch(PDO::FETCH_ASSOC);

            if (!$userData) {
                return $this->createErrorResponse('User not found', Response::HTTP_NOT_FOUND);
            }

            $user = new User(
                $userData['id'],
                $userData['first_name'],
                $userData['role_id'],
                $userData['last_name'],
                $userData['email'],
                $userData['password'],
                $userData['created_at'],
                $userData['updated_at']
            );

            return new JsonResponse($user);

        } catch (PDOException $e) {
            return $this->createErrorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createUser(Request $request)
    {
        try 
        {
            $data = json_decode($request->getContent(), true);

            if (!$this->validateUserData($data)) 
            {
                return $this->createErrorResponse('Invalid user data', Response::HTTP_BAD_REQUEST);
            }

            $connection = $this->database->getConnection();

            $query = $connection->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
            $query->execute([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password'], 
            ]);

            $newUserId = $connection->lastInsertId();
            $newUser = $this->getUser($newUserId);

            return new JsonResponse($newUser, Response::HTTP_CREATED);

        } catch (PDOException $e) {
            return $this->createErrorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    private function createErrorResponse($message, $statusCode)
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }

    private function validateUserData($data)
    {
        return isset($data['first_name']) && isset($data['last_name']) && isset($data['email']) && isset($data['password']);
    }
}
