<?php
$application = 'darkblog';
require_once '../conf/conf.php';

require_once '../autoloader.php';

$autoloader = new libAutoloader();

require_once '../conf/db.php';
require_once '../conf/emercoin.conf.php';
require_once '../conf/other.php';

$page = 'posts';

if(!isset($_SESSION['filter']))
    $_SESSION['filter'] = array(
        'sort' => false,
        'expired' => 0,
        'search' => ''
    );

require 'templates/header.php';

\darkblog\other\url::parse();

if(isset($_POST['action']) && isset($_POST['username']) && isset($_POST['postname']) && isset($_POST['userkey']) && ($_POST['action'] == 'signpost')) {
    $oBloggers = new \darkblog\objects\users();
    ob_clean();
    
    try {
        echo $oBloggers->signPost($_POST['username'], $_POST['postname'], $_POST['userkey']);
    } catch (Exception $ex) {
        echo '';
        die();
    }
    
    die();
}

$oPosts = new \darkblog\objects\posts();

$posts = $oPosts->getMyPosts();

//var_dump($posts);

//require 'templates/posts.php';

require 'templates/posts_footer.php';

require 'templates/footer.php';