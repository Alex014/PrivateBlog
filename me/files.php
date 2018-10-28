<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'files';

require 'templates/header.php';

\darkblog\other\url::parse();

try {
    \darkblog\lib\emercoin::getinfo();
} catch (Exception $exc) {
    $error = 'Error';
    $description = $exc->getMessage();
    if(strpos($description, 'Connection refused') !== false)
        $error = 'Connection refused';
    elseif(strpos($description, 'passphrase') !== false)
        $error = 'Wallet locked';
    require 'templates/error.php';
    require 'templates/footer.php';
    die();
}




$oFiles = new \darkblog\objects\files();

$files = $oFiles->getMyFiles();

//var_dump($files);
require 'templates/files.php';

require 'templates/footer.php';