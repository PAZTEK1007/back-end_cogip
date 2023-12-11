<?php
namespace App\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Error
{
   public static function createErrorResponse($message, $statuscode)
   {
      return new JsonResponse(['error' => $message], $statuscode);
   }
}
