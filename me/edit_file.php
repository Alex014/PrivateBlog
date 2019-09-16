<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'new_file';

if(!isset($_REQUEST['name'])) {
    ob_clean();
    header("location: '/me/files.php'");
}
else {
    $filename = $_REQUEST['name'];
}

require __DIR__.'/templates/header.php';

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
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'File record updated';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}
elseif(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) {
    $oFiles = new \darkblog\objects\files();
    
    try {
        $oFiles->deleteFile($filename);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'File record deleted';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}
else {
    $oFiles = new \darkblog\objects\files();
    $file = $oFiles->getFileData($filename);
}


$err = '';

try {
    $emercoinaddress = darkblog\lib\emercoin::getFirstAddress();
    $signature = darkblog\lib\emercoin::signmessage($emercoinaddress, ''.time());
} catch (Exception $ex) {
    $err = $ex->getMessage();
}


if(empty($err)) {
    require __DIR__.'/templates/files_edit.php';
}
elseif(strpos($err, 'walletpassphrase') !== false) {
    $error = 'Wallet locked';
    $description = '<br>You must unlock the wallet<br> to update records or <br>sign the data using keys';
    require __DIR__.'/templates/error.php';
}
elseif(strpos($err, 'block minting only') !== false) {
    $error = 'Wallet unlocked for block minting only';
    $description = '<br>You must unlock your wallet completely';
    require __DIR__.'/templates/error.php';
}
elseif(strpos($err, 'Connection refused') !== false) {
    $error = 'Connection refused';
    $description = $err;
}
else {
    $error = 'Unknown error';
    $description = $err;
    require __DIR__.'/templates/error.php';
}
//var_dump($blogger);
require __DIR__.'/templates/footer.php';