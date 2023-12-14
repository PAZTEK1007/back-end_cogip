<?php

namespace App\Model;

use Firebase\JWT\JWT;

class Auth
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function authenticate($email, $password)
    {
        $token = null;
        $error = null;

        try {
            if ($this->isValidCredentials($email, $password)) {
                $token = $this->generateToken($email);
            } else {
                $error = "Invalid credentials";
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return ['token' => $token, 'error' => $error];
    }

    private function isValidCredentials($email, $password)
    {
        // Vérifiez les informations d'authentification (exemple très simple)
        return $email === "john.doe@example.com" && $password === "test123";
    }

    private function generateToken($email)
    {
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
        try {
            $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
            return $decoded;
        } catch (\Throwable $e) {
            return false;
        }
    }
    
}
