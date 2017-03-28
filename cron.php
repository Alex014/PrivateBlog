<?php
$application = 'darkblog';
require_once 'conf/conf.php';

require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';

//$posts = darkblog\lib\emercoin::name_filter('^blogger:.+', 0, 0, 0);
//var_dump($posts);
//die();

$users = new \darkblog\objects\users();
$posts = new \darkblog\objects\posts();

$posts->clearAll();
$users->importUsers();
$posts->importPosts();

echo 'ok';

//https://www.base64-image.de/