<?php

namespace App\Model;

use InvalidArgumentException;

class Validator
{
    public static function validateString($value, $minLength = null, $maxLength = null)
    {
        if ($minLength !== null && strlen($value) < $minLength) {
            throw new InvalidArgumentException("La longueur minimale de la chaîne n'est pas satisfaite.");
        }

        if ($maxLength !== null && strlen($value) > $maxLength) {
            throw new InvalidArgumentException("La longueur maximale de la chaîne est dépassée.");
        }
    }
}
