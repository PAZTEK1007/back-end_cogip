<?php

namespace App\Queries;

use PDO;
use PDOException;

class Database
{
    private static $instance;
    private $connection;

    private $host;
    private $dbname;
    private $user;
    private $password;


    private function __construct($host, $dbname, $user, $password)
    {
        $this->user;
        $this->host;
        $this->dbname;
        $this->password;

        try 
        {
            // Votre code d'initialisation de la connexion à la base de données ici
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) 
        {
            // Gérer les erreurs de connexion ici
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) 
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

