<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'new_post';

require 'templates/header.php';

\darkblog\other\url::parse();

if(!empty($_POST['content']) && !empty($_POST['name'])) {
    $oPosts = new \darkblog\objects\posts();
    
    $name = trim($_POST['name']);
    $days = (int)$_POST['days'];
    $vars = array();
    //TODO: move everything to newPost
    if(!empty($_POST['title'])) $vars['title'] = trim($_POST['title']);
    if(!empty($_POST['lang'])) $vars['lang'] = trim($_POST['lang']);
    if(!empty($_POST['username'])) $vars['username'] = trim($_POST['username']);
    if(!empty($_POST['sig'])) $vars['sig'] = trim($_POST['sig']);
    if(!empty($_POST['keywords'])) $vars['keywords'] = trim($_POST['keywords']);
    if(!empty($_POST['reply'])) $vars['reply'] = trim($_POST['reply']);
    
    //var_dump($vars); die();
     
    try {
        
        $oPosts->newPost($_POST['name'], trim($_POST['content']), $vars, $days);

        /*foreach ($build_output as $index => $value) {
            echo $index;
            if($index == 0) {
                \darkblog\lib\emercoin::name_new('blog:'.$name, $value, $days, 'base64', 'base64');
            }
            else {
                \darkblog\lib\emercoin::name_new('blog:'.$name.'_'.$index, $value, $days, 'base64', 'base64');
            }
        }*/
        
    } catch (Exception $exc) {
        $error = $exc->getMessage();
        $description = $exc->getTraceAsString();
        
        require 'templates/error.php';
        require 'templates/footer.php';
        
        die();
    }
    
    $message = 'Post record created';
    $description = ', it will be updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require 'templates/success.php';
    require 'templates/footer.php';

    die();
}

require 'templates/posts_new.php';

require 'templates/footer.php';