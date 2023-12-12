<?php

// var_dump($_ENV);

require_once __DIR__ . '/vendor/autoload.php';
//charger les variabales d'envirronement à partir du fichier .env dans le répertoire courant
//Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->load();
require_once __DIR__ . '/Core/Helper.php';
require_once __DIR__ . '/Routes/Routes.php';
