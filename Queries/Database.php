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
        $host = $_ENV["DB_HOST"] ?? null;
        $dbname = $_ENV["DB_NAME"] ?? null;
        $user = $_ENV["DB_USER"] ?? null;
        $password = $_ENV["DB_PASSWORD"] ?? null;

        try {
            // Votre code d'initialisation de la connexion à la base de données ici
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
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
