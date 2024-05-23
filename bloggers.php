<?php
$application = 'darkblog';
require_once 'conf/conf.php';

require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$page = 'bloggers';

require 'templates/header.php';

$users = new \darkblog\objects\users();

\darkblog\other\url::parse();

if (array_key_exists('order', $_GET)) {
    if($_GET['order'] == 'username') {
        \darkblog\db\pager::$order = 'username';
    } elseif ($_GET['order'] == 'total') {
        \darkblog\db\pager::$order = 'posts DESC';
    }
}
    
if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];

if(!empty($_GET['id'])) {
    
    $user = $users->getUser((int)$_GET['id']);
    
    require 'templates/blogger.php';
}
elseif(!empty($_GET['name'])) {
    
    $user = $users->getUserByName($_GET['name']);
    
    require 'templates/blogger.php';
}
else {
    
    $users = $users->getUsers();

    require 'templates/bloggers.php';
}

require 'templates/footer.php';