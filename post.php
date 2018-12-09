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
    
    if(isset($_GET['full'])) {
        $post = $oposts->getPost((int)$_GET['id']);

        if(!empty($post['id'])) {
            $post['replies'] = $oposts->getRepliesFull($post['id']);
            if(!empty($post['reply_id']))
                $post['reply_to'] = $oposts->getPost($post['reply_id']);
        }
        
        require 'templates/header.php';
        require 'templates/post_full.php';
    }
    else {
        $post = $oposts->getPost((int)$_GET['id']);

        if(!empty($post['id'])) {
            $post['replies'] = $oposts->getReplies($post['id']);
            if(!empty($post['reply_id']))
                $post['reply_to'] = $oposts->getPost($post['reply_id']);
        }
        
        require 'templates/header.php';
        require 'templates/post.php';
    }
    
}
elseif(!empty($_GET['name'])) {
    $oposts = new \darkblog\objects\posts();
    
    if(isset($_GET['full'])) {
        $post = $oposts->getPostByName($_GET['name']);

        if(!empty($post['id'])) {
            $post['replies'] = $oposts->getRepliesFull($post['id']);
            if(!empty($post['reply_id']))
                $post['reply_to'] = $oposts->getPost($post['reply_id']);
        }
        
        require 'templates/header.php';
        require 'templates/post_full.php';
    }
    else {
        $post = $oposts->getPostByName($_GET['name']);

        if(!empty($post['id'])) {
            $post['replies'] = $oposts->getReplies($post['id']);
            if(!empty($post['reply_id']))
                $post['reply_to'] = $oposts->getPost($post['reply_id']);
        }
        
        require 'templates/header.php';
        require 'templates/post.php';
    }
    
}
else {
    require 'templates/header.php';
}

require 'templates/footer.php';

if((!empty($_GET['name']) || !empty($_GET['id'])) && isset($_GET['full'])) {
    require 'templates/post_full_footer.php';
}