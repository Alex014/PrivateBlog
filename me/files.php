<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'files';

require __DIR__.'/templates/header.php';

\darkblog\other\url::parse();

try {
    \darkblog\lib\emercoin::getinfo();
} catch (Exception $exc) {
    $error = 'Error';
    $description = $exc->getMessage();
    if(strpos($description, 'Connection refused') !== false)
        $error = 'Connection refused';
    else
        $error = 'Unknown error';
    require __DIR__.'/templates/error.php';
    require __DIR__.'/templates/footer.php';
    die();
}




if(empty($error)) {
    $oFiles = new \darkblog\objects\files();

    $files = $oFiles->getMyFiles();
}

//var_dump($files);
require __DIR__.'/templates/files.php';

require __DIR__.'/templates/footer.php';