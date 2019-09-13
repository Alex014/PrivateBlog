<?php
$application = 'darkblog';
require_once 'conf/conf.php';

$application = 'darkblog';
require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$page = 'config';

$olangs = new \darkblog\objects\langs();

$languages = $olangs->selectAll();

if(isset($_POST['lang']) && isset($_POST['records'])) {
    $_SESSION['lang'] = $_POST['lang'];
    $_SESSION['records'] = (int)$_POST['records'];
    if($_SESSION['records'] < 10) $_SESSION['records'] = 10;
}

$lang = $_SESSION['lang'];
$records = $_SESSION['records'];

if(empty($lang)) $lang = '';
if(empty($records)) $records = 100;

var_dump($_SESSION);

require 'templates/header.php';
require 'templates/config.php';
require 'templates/footer.php';