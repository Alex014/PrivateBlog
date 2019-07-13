<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'new_post';

require __DIR__.'/templates/header.php';

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
        
        require __DIR__.'/templates/error.php';
        require __DIR__.'/templates/footer.php';
        
        die();
    }
    
    $message = 'Post record created';
    $description = ', it will be updated in this client in 10 min or later ... <br> Click the [SYNC] button';
    $link = '/me/posts.php';
    $link_name = 'Posts list';
        
    require __DIR__.'/templates/success.php';
    require __DIR__.'/templates/footer.php';

    die();
}

if(!empty($_GET['reply'])) {
    $__reply = $_GET['reply'];
}
else {
    $__reply = '';
}

require __DIR__.'/templates/posts_new.php';

require __DIR__.'/templates/footer.php';