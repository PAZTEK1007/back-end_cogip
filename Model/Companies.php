<?php

namespace App\Model;


class Companies
{
    public int $id;
    public string $name;
    public string $created_at;
    public string $updated_at;
    public function __construct(int $id, string $name, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

class Types
{
    public int $id;
    public string $name;
    public int $type_id;
    public string $country;
    public string $tva;
    public string $created_at;
    public string $updated_at;
    public function __construct(int $id, string $name, int $type_id, string $country, string $tva, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type_id = $type_id;
        $this->country = $country;
        $this->tva = $tva;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}


class Invoices
{
    public int $id;
    public string $ref;
    public int $id_company;
    public string $created_at;
    public string $updated_at;
    public function __construct(int $id, string $ref, int $id_company, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->ref = $ref;
        $this->id_company = $id_company;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}


class Contacts
{
    public int $id;
    public string $name;
    public int $company_id;
    public string $email;
    public string $phone;
    public string $created_at;
    public string $updated_at;
    public function __construct(int $id, string $name, int $company_id, string $email, string $phone, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->company_id = $company_id;
        $this->email = $email;
        $this->phone = $phone;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
