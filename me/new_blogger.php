<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'new_blogger';

require __DIR__.'/templates/header.php';

\darkblog\other\url::parse();

if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'new')) {
    $oBloggers = new \darkblog\objects\users();
    
    $days = (int)$_REQUEST['days'];
    $name = $_REQUEST['username'];
    $data = array();
    
    if(!empty($_REQUEST['content'])) {
        $data['descr'] = trim($_REQUEST['content']);
    }

    if(!empty($_REQUEST['userkey'])) {
        $data['key'] = trim($_REQUEST['userkey']);
    }
    
    if(!empty($_REQUEST['sig'])) {
        $data['sig'] = trim($_REQUEST['sig']);
    }
    
    //var_dump($name, $data, $days);

    try {
        $oBloggers->newUser($name, $data, $days);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'Blogger record created';
    $description = ', it will be updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/bloggers.php';
    $link_name = 'Bloggers list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}

require __DIR__.'/templates/bloggers_new.php';

require __DIR__.'/templates/footer.php';