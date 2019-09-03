<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

if(isset($_GET['action']) && ($_GET['action'] == 'keys')) {
    ob_clean();
    echo json_encode(darkblog\lib\emercoin::listAccountsAddresses());
    die();
}
elseif(isset($_GET['action']) && ($_GET['action'] == 'bloggers')) {
    $oBloggers = new \darkblog\objects\users();
    ob_clean();
    echo json_encode($oBloggers->getMyUsers());
    die();
}
elseif(isset($_POST['action']) && isset($_POST['username']) && isset($_POST['userkey']) && ($_POST['action'] == 'signblogger')) {
    $oBloggers = new \darkblog\objects\users();
    ob_clean();
    
    try {
        echo $oBloggers->signBlogger($_POST['username'], $_POST['userkey']);
    } catch (Exception $ex) {
        echo '';
        die();
    }
    
    die();
}

$page = 'bloggers';

require __DIR__.'/templates/header.php';

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
    require __DIR__.'/templates/error.php';
    require __DIR__.'/templates/footer.php';
    die();
}




$oBloggers = new \darkblog\objects\users();

$bloggers = $oBloggers->getMyUsers();

//var_dump($bloggers);dfghfd
require __DIR__.'/templates/bloggers.php';

require __DIR__.'/templates/footer.php';