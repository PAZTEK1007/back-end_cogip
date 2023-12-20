<?php

namespace App\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Model\BaseModel;
use App\Controller\HomeController;
use App\Model\User;
use stdClass;

class Auth extends BaseModel
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function authenticate($email, $password)
    {
        try
        {
            $userModel = new User();
        
            $user = $userModel->getUserByEmail($email);
            // Vérification de la validité du JSON
            $jsonUser = json_decode($user, true);

            // Vérification des clés avant d'y accéder
            $pwdUser = $jsonUser['data']['password'];
            $roleUser = $jsonUser['data']['role_id'];
            $emailUser = $jsonUser['data']['email'];
            
            // Vérification du mot de passe
            if ($password === $pwdUser) 
            {   
                
                if ($roleUser === 1) 
                {
                    $token = $this->generateToken($emailUser, $pwdUser, $roleUser);
        
                    // Envoi du token dans le header de la réponse
                    header('Content-Type: application/json');
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Headers: Origins, X-Requested-With, Content-type, Accept');
                    header('Authorization: Bearer ' . $token);
                    http_response_code(200);
        
                    echo json_encode([
                        'message' => 'Authentification réussie'
                    ]);
                return;
                } 
                else if ($roleUser === 2) 
                {
                    $token = $this->generateToken($emailUser, $pwdUser, $roleUser);
        
                    // Envoi du token dans le header de la réponse
                    header('Content-Type: application/json');
                    header('Access-Control-Allow-Origin: localhost:5173');
                    header('Access-Control-Allow-Headers: Origins, X-Requested-With, Content-type, Accept');
                    header('Authorization: Bearer ' . $token);
                    http_response_code(200);
        
                    echo json_encode([
                        'message' => 'Authentification réussie'
                    ]);
                return;
                }
                else 
                {
                    throw new \Exception("Enregistrez-vous !", 401);
                }
            } 
            else 
            {
                throw new \Exception("Email ou mot de passe incorrect", 401);
            }
        }
        catch (\Throwable $e) 
        {
            http_response_code(401);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
    
    private function generateToken($email, $password, $role)
    {
        $tokenPayload = 
        [
            "iss" => "localhost:5173",
            "aud" => "localhost:5173",
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => 
            [
                "email" => $email,
                "password" => $password,
                "role_id" => $role
            ]
        ];
    
        $token = JWT::encode($tokenPayload, $this->secretKey, 'HS256');
    
        return $token;
    }
 
    public function verifyToken($token)
    {
        try 
        {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return true;          
        } 
        catch (\Throwable $e) 
        {
            return false;
        }
    }

    public function checkAuthorization($user)
    {
        if ($user['role_id'] === 1) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }
}

