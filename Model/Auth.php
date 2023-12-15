<?php

namespace App\Model;

use Firebase\JWT\JWT;
use App\Model\BaseModel;
use App\Controller\HomeController;
use App\Model\User;

class Auth extends BaseModel
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }
    
    public function authenticate($email, $password)
    {
        // Création d'une instance de la classe User
        $userModel = new User();

        // Appel de la méthode getUserByEmail
        $user = $userModel->getUserByEmail($email);

        $jsonUser = json_decode($user, true);
        
        $pwdUser = $jsonUser['data']['password'];
       
        


        // Vérification du mot de passe
        if ($password === $pwdUser) 
        {
            // Génération du token
            $token = $this->generateToken($email, $password);

            return ['token' => $token];
        } 
        else 
        {
            throw new \Exception("Email ou mot de passe incorrect", 401);
        }
    }

    private function generateToken($email, $password)
    {
        // Génération du token JWT
        $tokenPayload = 
        [
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "exp" => time() + 3600,
            "email" => $email,
            "password" => $password,
        
        ];

        echo JWT::encode($tokenPayload, $this->secretKey, 'HS256');
    }

    public function verifyToken($token)
    {
        // Vérification du token JWT
        try 
        {
            return JWT::decode($token, $this->secretKey, ['HS256']);
        } 
        catch (\Throwable $e) 
        {
            return false;
        }
    }
}
