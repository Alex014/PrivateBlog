<?php
$application = 'darkblog';
require_once 'conf/conf.php';

$application = 'darkblog';
require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$page = 'about';

$posts = new \darkblog\objects\posts();

require 'templates/header.php';
require 'templates/about.php';
require 'templates/footer.php';