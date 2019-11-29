<?php
//phpinfo(); die();
//Checking for extensions ...
$errors = [];

if(!function_exists('mb_check_encoding')) {
    $errors[] = "Extension '<b>mbstring</b>' not found";
}

if(!function_exists('curl_init')) {
    $errors[] = "Extension '<b>curl</b>' not found";
}

if(!class_exists('SQLite3')) {
    $errors[] = "Extension '<b>sqlite3</b>' not found";
}

if(!empty($errors)) {
    ob_clean();
    require 'templates/error.php';
    die();
}

require_once 'bloggers.php';


//https://www.base64-image.de/