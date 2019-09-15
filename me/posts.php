<?php
$application = 'darkblog';
require_once __DIR__.'/../conf/conf.php';

require_once __DIR__.'/../autoloader.php';

$autoloader = new libAutoloader();

require_once __DIR__.'/../conf/db.php';
require_once __DIR__.'/../conf/emercoin.conf.php';
require_once __DIR__.'/../conf/other.php';

$page = 'posts';

if(!isset($_SESSION['filter']))
    $_SESSION['filter'] = array(
        'sort' => false,
        'expired' => 0,
        'search' => ''
    );


if(isset($_POST['action']) && isset($_POST['username']) && isset($_POST['postname']) && isset($_POST['userkey']) && ($_POST['action'] == 'signpost')) {
    $oBloggers = new \darkblog\objects\users();
    ob_clean();
    //try {
        echo $oBloggers->signPost($_POST['username'], $_POST['postname'], $_POST['userkey']);
    //} catch (Exception $ex) {
    //    echo '';
    //}
    
    die();
}
else {
    require __DIR__.'/templates/header.php';

    \darkblog\other\url::parse();
}

$oPosts = new \darkblog\objects\posts();

$posts = $oPosts->getMyPosts();

//var_dump($posts);

//require __DIR__.'/templates/posts.php';

require __DIR__.'/templates/posts_footer.php';

require __DIR__.'/templates/footer.php';