<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'new_file';

if(!isset($_REQUEST['name'])) {
    ob_clean();
    header("location: '/me/files.php'");
}
else {
    $filename = $_REQUEST['name'];
}

require 'templates/header.php';

\darkblog\other\url::parse();

if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edit')) {
    $oFiles = new \darkblog\objects\files();
    
    $filename = trim($_REQUEST['filename']);
    $days = (int)$_REQUEST['days'];
    $value = trim($_REQUEST['content']);
    
    try {
        $oFiles->editFile($filename, $value, $days);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'File record updated';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}
elseif(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) {
    $oFiles = new \darkblog\objects\files();
    
    try {
        $oFiles->deleteFile($filename);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'File record deleted';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}
else {
    $oFiles = new \darkblog\objects\files();
    $file = $oFiles->getFileData($filename);
}

require 'templates/files_edit.php';
//var_dump($blogger);
require 'templates/footer.php';