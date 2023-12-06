<?php

namespace App\Model;

use App\Queries\Database;
use PDO;

class User
{

    ////////GET ALL USERS//////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllUsers()
    {
        // Utilisez la classe Database pour obtenir une connexion
        $database = Database::getInstance();
        $connection = $database->getConnection();

        //requête pour récupérer tous les users
        $query = $connection->prepare(
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

        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        header('Content-Type: application/json');
        echo $jsonData;
    }


    //////GET FIRST FIVE USERS/////////////////////////////////////////////////////////////////////////////////////////

    //requête pour récupérer les 5premiers users
    public function getFirstFive()
    {
        // Utilisez la classe Database pour obtenir une connexion
        $database = Database::getInstance();
        $connection = $database->getConnection();
        $query = $connection->prepare(
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

        // Définir les en-têtes pour indiquer que la réponse est au format JSON
        header('Content-Type: application/json');
        echo $jsonData;
    }
}
