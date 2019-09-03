<?php
$application = 'darkblog';

//define('PHAR', false);
//define('SQLITE', true);

error_reporting(E_ALL - E_NOTICE - E_DEPRECATED - E_WARNING);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8"); 
mb_regex_encoding('UTF-8');

require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/conf.php';
require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';

$users = new \darkblog\objects\users();
$posts = new \darkblog\objects\posts();

$posts->clearAll();
$users->importUsers();
$posts->importPosts();

ob_clean();
echo 'OK';