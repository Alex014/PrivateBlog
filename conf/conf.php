<?php
if(!isset($application)) die("Can not call directly");

define('PHAR', true);
define('SQLITE', true);

if(PHAR) {
    error_reporting(E_ALL - E_NOTICE - E_DEPRECATED);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL - E_NOTICE - E_DEPRECATED);
    ini_set('display_errors', 1);
}

require_once __DIR__.'/config.php';

mb_internal_encoding("UTF-8"); 
mb_regex_encoding('UTF-8');
