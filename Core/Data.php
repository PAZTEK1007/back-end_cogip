<?php

namespace App\Core;

class Data
{
    private $url;
    private $dbName;
    private $user;
    private $password;

    private $link;

    public function __construct($url, $dbName, $user, $password)
    {
        $this->url = $url;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;

        $this->link = null;
    }

    public function connect()
    {
        try 
        {
            $this->link = new \PDO('mysql:host=' . $this->url . ';dbname=' . $this->dbName . ';charset=utf8', $this->user, $this->password);
            $this->link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->link;
            
        } 
        catch (\PDOException $e) 
        {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public function isConnected()
    {
        return $this->link !== null;
    }

    public function query($query)
    {
        return $this->link->query($query);
    }

    public function disconnect()
    {
        $this->link = null;
    }
}

$bdd = new Data('localhost', 'cogip_project', 'root', 'root');
$pdo = $bdd->connect();