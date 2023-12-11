<?php

namespace App\Core;

class Controller
{
    /*
    
@var $view, $data
return view*/
    public function view($view, $data = [])
    {
        extract($data);
        require_once(__ROOT__ . '/Ressources/views/' . $view . '.php');
    }
    public function sendJsonResponse($response)
    {
        echo $response;

        $statusCode = $response instanceof JsonResponse ? $response->getStatusCode() : Response::HTTP_OK;
        http_response_code($statusCode);

        foreach ($response->headers->all() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }
}
