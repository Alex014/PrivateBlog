<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';



$oPosts = new \darkblog\objects\posts();

$_posts = $oPosts->getMyPosts();
$posts = array();

if(!empty($_REQUEST['sort']))
    $sort = $_REQUEST['sort'];
else
    $sort = '';

$expired = (int)$_REQUEST['expired'];

if(!empty($_REQUEST['search']))
    $search = $_REQUEST['search'];
else
    $search = '';

$_SESSION['filter'] = array(
    'sort' => $sort,
    'expired' => $expired,
    'search' => $search
); 

//unset($_SESSION['filter']);

//var_dump($_REQUEST);
//var_dump($_REQUEST['expired'], (bool)$_REQUEST['expired'], $_SESSION['filter']);


foreach ($_posts as $post) {
    $equal = true;
    
    //Expired
    if(!$expired)
        if($post['expired']) $equal = false;
    //Name/Title
    if(!empty($search)) {
        if(isset($post['vars']['title'])) {
            if(strpos(mb_strtolower($post['vars']['title']), mb_strtolower($search)) === false)
                $equal = false;
        }
        else {
            if(strpos(mb_strtolower($post['name']), mb_strtolower($search)) === false)
                $equal = false;
        }
    }
    
    if($equal) $posts[] = $post;
}

//Sort:

usort($posts, function ($post1, $post2) use($sort) {
    //Title(name)
    if($sort == 'ta') {
        /*if(isset($post1['vars']['title']) && isset($post2['vars']['title'])) {
            return strcmp($post1['vars']['title'], $post2['vars']['title']);
        } else {*/
            return strcmp($post1['name'], $post2['name']);
        //}
    }
    elseif($sort == 'td') {
        /*if(isset($post2['vars']['title']) && isset($post1['vars']['title'])) {
            return strcmp($post2['vars']['title'], $post1['vars']['title']);
        } else {*/
            return strcmp($post2['name'], $post1['name']);
        //}
    }
    
    //Expires in
    elseif($sort == 'xa') {
        if($post1['expires_in'] > $post2['expires_in']) {
            return 1;
        }
        elseif($post1['expires_in'] < $post2['expires_in']) {
            return -1;
        }
        else {
            return 0;
        }
    }
    elseif($sort == 'xd') {
        if($post2['expires_in'] > $post1['expires_in']) {
            return 1;
        }
        elseif($post2['expires_in'] < $post1['expires_in']) {
            return -1;
        }
        else {
            return 0;
        }
    }
    
    return 0;
});

//var_dump($posts);
require __DIR__.'/templates/posts.php';