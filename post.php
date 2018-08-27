<?php
$application = 'darkblog';
require_once 'conf/conf.php';

$application = 'darkblog';
require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

require_once 'darkblog/other/text.php';

$page = 'post';

\darkblog\other\url::parse();

\darkblog\db\pager::$order = 'name';
if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];

if(!empty($_GET['id'])) {
    $oposts = new \darkblog\objects\posts();
    
    $post = $oposts->getPost((int)$_GET['id']);
    
    $post['replies'] = $oposts->getReplies($post['id']);
    if(!empty($post['reply_id']))
        $post['reply_to'] = $oposts->getPost($post['reply_id']);
    
    require 'templates/header.php';
    require 'templates/post.php';
}
if(!empty($_GET['name'])) {
    $oposts = new \darkblog\objects\posts();
    
    $post = $oposts->getPostByName($_GET['name']);

    if(!empty($post['id'])) {
        $post['replies'] = $oposts->getReplies($post['id']);
        if(!empty($post['reply_id']))
            $post['reply_to'] = $oposts->getPost($post['reply_id']);
    }
    else {
        
    }
    
    require 'templates/header.php';
    require 'templates/post.php';
}
else {
    require 'templates/header.php';
}

require 'templates/footer.php';