<?php

namespace App\Queries;

use PDO;
use PDOException;

class Database
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $host = "db4free.net:3306";
        $dbname = "cogip_project";
        $user = "keller6";
        $password = "Le_Mot_De_Passe_:_Cogip_Project_Keller_6";

        try {
            // Votre code d'initialisation de la connexion à la base de données ici
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion ici
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
