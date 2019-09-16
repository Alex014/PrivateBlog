<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'new_post';

if(!isset($_REQUEST['name'])) {
    ob_clean();
    header("location: '/me/posts.php'");
}
else {
    $name = $_REQUEST['name'];
}

require __DIR__.'/templates/header.php';

\darkblog\other\url::parse();

if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edit')) {
    $oPosts = new \darkblog\objects\posts();
    
    $days = (int)$_POST['days'];
    $vars = array();
    //TODO: move everything to newPost
    if(!empty($_POST['title'])) $vars['title'] = trim($_POST['title']);
    if(!empty($_POST['lang'])) $vars['lang'] = trim($_POST['lang']);
    if(!empty($_POST['username'])) $vars['username'] = trim($_POST['username']);
    if(!empty($_POST['sig'])) $vars['sig'] = trim($_POST['sig']);
    if(!empty($_POST['keywords'])) $vars['keywords'] = trim($_POST['keywords']);
    if(!empty($_POST['reply'])) $vars['reply'] = trim($_POST['reply']);
    
    //$build_output = darkblog\lib\parser::build($_POST['content'], $name, $vars);
    
    //var_dump($build_output); die();
    
    try {
        $oPosts->editPost($name, trim($_POST['content']), $vars, $days);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'Post record updated';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}
elseif(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) {
    $oPosts = new \darkblog\objects\posts();
    
    try {
        $oPosts->deletePost($name);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'Post record deleted';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}
else {
    $oPosts = new \darkblog\objects\posts();
    $post = $oPosts->getPostData($name);
}


$err = '';

try {
    $emercoinaddress = darkblog\lib\emercoin::getFirstAddress();
    $signature = darkblog\lib\emercoin::signmessage($emercoinaddress, ''.time());
} catch (Exception $ex) {
    $err = $ex->getMessage();
}

if(empty($err)) {
    require __DIR__.'/templates/posts_edit.php';
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