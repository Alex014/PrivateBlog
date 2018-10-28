<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'new_file';

require 'templates/header.php';

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
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'File record created';
    $description = ', it will be updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/files.php';
    $link_name = 'Files list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}

require 'templates/files_new.php';

require 'templates/footer.php';