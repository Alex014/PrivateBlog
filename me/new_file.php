<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'new_file';

require __DIR__.'/templates/header.php';

\darkblog\other\url::parse();

if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'new') && isset($_FILES['file']) && ($_FILES['file']['error'] == 0)) {
    $oFiles = new \darkblog\objects\files();
    
    $days = (int)$_REQUEST['days'];
    $name = $_REQUEST['name'];
    $data = array();
    
    $value = trim($_REQUEST['content']);

    $parts = \darkblog\lib\files::split_to_array($_FILES['file']['tmp_name']);
    $count = count($parts);
    
    $main_part = json_encode(array(
        'name' => $_FILES['file']['name'],
        'content_type' => $_FILES['file']['type'],
        'parts' => $count
    ));
    
    $files = array();

    try {
        
        $files[$name] = $main_part;

        for($i = 0; $i < $count; $i++) {
            $files[$name.':'.($i+1)] = $parts[$i];
        }
        
        $oFiles->newFiles($files, $days);
        
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'File record created';
    $description = ', it will be updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}



$err = '';

try {
    $emercoinaddress = darkblog\lib\emercoin::getFirstAddress();
    $signature = darkblog\lib\emercoin::signmessage($emercoinaddress, ''.time());
} catch (Exception $ex) {
    $err = $ex->getMessage();
}

if(empty($err)) {
    require __DIR__.'/templates/files_new.php';
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

require __DIR__.'/templates/footer.php';