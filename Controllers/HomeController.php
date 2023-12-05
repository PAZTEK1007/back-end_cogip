<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Queries\Database;
use App\Model\User;
use PDO;

class HomeController extends Controller
{

    /*
    * return view
    */
    public function index()
    {
        $users = $this->getUsers();

        // Passez les données à la vue
        return $this->view('welcome', ["name" => "Cogip"]);
    }

    private function getUsers()
    {
        // Utilisez la classe Database pour obtenir une connexion
        $database = Database::getInstance();
        $connection = $database->getConnection();

        // Exemple de requête pour récupérer des données
        $query = $connection->prepare("SELECT * FROM users");
        $query->execute();
        $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

        // Récupérez les résultats sous forme d'objets User
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

        var_dump($users);
        return $this->view('welcome', ["name" => "Cogip", "users" => $users]);
    }
}
