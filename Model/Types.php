<?php

namespace App\Model;

use App\Model\BaseModel;
use PDO;


class Types extends BaseModel
{
    //vérifier si le type existe déjà et récupérer son ID si c'est le cas
    public function getTypeIdByName($type_name)
    {
        $query = $this->connection->prepare("SELECT id FROM types WHERE name= :type_name");
        $query->bindParam(':type_name', $type_name);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        //retourner l'ID de type si une correspondance sinon retourner null
        return $result ? $result['id'] : null;
    }
}
