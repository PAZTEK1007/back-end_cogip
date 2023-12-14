<?php

// namespace App\Model;

// use DateInvalidOperationException;
// use DragonBe\Vies\Vies;
// use DragonBe\Vies\ViesException;
// use DragonBe\Vies\ViesServiceException;

// use DateTime;
// use InvalidArgumentException;

// class Validator
// {
//     public static function validateAndSanitize($value, $minLength = null, $maxLength = null, $type = null)
//     {
//         //utiliser condition ternaire pour vérifier si la valeur est définie et non vide
//         $sanitizedValue = isset($value) && !empty($value) ? htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8')  : null;

//         //si la valeur est vide ou null alors ->message d'erreur
//         if ($sanitizedValue === null) {
//             throw new InvalidArgumentException("La valeur ne peut pas être vide.");
//         }

//         //valider en fonction du type
//         if ($type !== null) {
//             switch ($type) {
//                 case 'name':
//                     self::validateName($sanitizedValue, $minLength, $maxLength);
//                     break;
//                 case 'email':
//                     self::validateEmail($sanitizedValue);
//                     break;
//                 case 'tva':
//                     self::validateTva($sanitizedValue);
//                     break;
//                 case 'date':
//                     self::validateDate($sanitizedValue);
//                     break;
//                 case 'phone':
//                     self::validatePhone($sanitizedValue);
//                     break;
//                 default:
//                     break;
//             }
//         }
//         return $sanitizedValue;
//     }

//     //valider les names
//     private static function validateName($value, $minLength = 3, $maxLength = 50)
//     {
//         //valider longueur
//         if (strlen($value) < $minLength || strlen($value) > $maxLength) {
//             throw new InvalidArgumentException("La longueur du nom doit être entre $minLength et $maxLength caractères.");
//         }

//         //valider la première lettre en maj
//         if (!ctype_upper($value[0])) {
//             throw new InvalidArgumentException("Le nom doit commencer par une lettre majuscule.");
//         }
//     }

//     //valider email
//     private static function validateEmail($value)
//     {
//         // Utiliser la fonction filter_var pour valider l'e-mail
//         if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
//             throw new InvalidArgumentException("L'adresse e-mail n'est pas valide.");
//         }
//     }

//     //valider tva
//     public static function validateTva($value)
//     {
//         // Extraire le pays et le numéro de TVA de la valeur
//         $countryCode = substr($value, 0, 2);
//         $tvaNumber = substr($value, 2);

//         // Créez une instance de la classe Vies
//         $vies = new Vies();

//         try {
//             // Valider le numéro de TVA avec VIES
//             $result = $vies->validateVat($countryCode, $tvaNumber);

//             // Vérifier si le numéro de TVA est valide
//             if (!$result->isValid()) {
//                 throw new DateInvalidOperationException("Le numéro de TVA n'est pas valide.");
//             }
//         } catch (ViesServiceException $e) {
//             // Gérer les erreurs temporaires du service VIES
//             throw new InvalidArgumentException("Erreur du service VIES : " . $e->getMessage());
//         } catch (ViesException $e) {
//             // Gérer les erreurs irrécupérables
//             throw new InvalidArgumentException("Erreur de validation du numéro de TVA : " . $e->getMessage());
//         }
//     }

//     //valider les dates
//     private static function validateDate($value)
//     {
//         // Valider que la date n'est pas avant la date du jour
//         $today = new DateTime();
//         $inputDate = new DateTime($value);
//         if ($inputDate < $today) {
//             throw new InvalidArgumentException("La date ne peut pas être antérieure à aujourd'hui.");
//         }
//     }

//     //valider le phone number
//     private static function validatePhone($value)
//     {
//         // Valider que le numéro de téléphone suit le format souhaité
//         if (!preg_match('/^[0-9\+\-\(\)\/\s]*$/', $value)) {
//             throw new InvalidArgumentException("Le numéro de téléphone n'est pas valide.");
//         }
//     }
// }
