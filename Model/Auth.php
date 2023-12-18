<?php

namespace App\Model;

use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\CoreException;
use App\Model\BaseModel;

class Auth extends BaseModel
{
    public $auth0;

    public function __construct()
    {
        $this->auth0 = new SdkConfiguration(
            domain: $_ENV["AUTH0_DOMAIN"],
            clientId: $_ENV["AUTH0_CLIENT_ID"],
            clientSecret: $_ENV["AUTH0_CLIENT_SECRET"],
            cookieSecret: $_ENV["AUTH0_COOKIE_SECRET"],
        );
    }

    public function verifyToken($token)
    {
        try {
            // Décodage du token avec Auth0
            $decodedToken = $this->auth0->decode($token);
            return $decodedToken;
        } catch (CoreException $e) {
            // Gestion spécifique des exceptions Auth0
            return false;
        } catch (\Throwable $e) {
            // Gestion générale des autres exceptions
            return false;
        }
    }
    public function logout()
    {
        $this->auth0->logout();
    }
}
