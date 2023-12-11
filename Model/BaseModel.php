<?php

namespace App\Model;

use App\Queries\Database;


class BaseModel
{
    protected $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }
}
