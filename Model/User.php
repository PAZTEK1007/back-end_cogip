<?php

namespace App\Model;

class User
{
    public int $id;
    public string $first_name;
    public int $role_id;
    public string $last_name;
    public string $email;
    public string $password;
    public string $created_at;
    public string $updated_at;

    public function __construct(int $id, string $first_name, int $role_id, string $last_name, string $email, string $password, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->role_id = $role_id;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}


class Roles
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


class Roles_permissions
{
    public int $id;
    public int $permission_id;
    public int $role_id;
    public function __construct(int $id, int $permission_id, int $role_id)
    {
        $this->id = $id;
        $this->permission_id = $permission_id;
        $this->role_id = $role_id;
    }
}


class Permissions
{
    public int $id;
    public string $created_at;
    public string $updated_at;
    public function __construct(int $id, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
