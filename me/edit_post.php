<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'new_post';

if(!isset($_REQUEST['name'])) {
    ob_clean();
    header("location: '/me/posts.php'");
}
else {
    $name = $_REQUEST['name'];
}

require 'templates/header.php';

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
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'Post record updated';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}
elseif(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) {
    $oPosts = new \darkblog\objects\posts();
    
    try {
        $oPosts->deletePost($name);
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'Post record deleted';
    $description = ', it will updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}
else {
    $oPosts = new \darkblog\objects\posts();
    $post = $oPosts->getPostData($name);
}

require 'templates/posts_edit.php';

require 'templates/footer.php';