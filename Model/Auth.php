<?php

namespace App\Model;

use Firebase\JWT\JWT;
use App\Model\BaseModel;

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

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Génération du token
            $token = $this->generateToken($email);

            return ['token' => $token];
        } else {
            throw new \Exception("Email ou mot de passe incorrect", 401);
        }
    }

    private function generateToken($email)
    {
        // Génération du token JWT
        $tokenPayload = [
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "exp" => time() + 3600,
            "sub" => $email,
        ];

        return JWT::encode($tokenPayload, $this->secretKey, 'HS256');
    }

    public function verifyToken($token)
    {
        // Vérification du token JWT
        try {
            return JWT::decode($token, $this->secretKey, ['HS256']);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
