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

$db = config::$db;

$result = [];

$q = $db->query("SELECT * FROM session ");
while ($row = $q->fetchArray()) {
    $result[] = $row;
}
                
// var_dump($result);
// var_dump($_SESSION);

// var_dump(\darkblog\lib\emercoin::listlabels());

// var_dump(\darkblog\lib\emercoin::getAddressesByLabel(""));

$addr = \darkblog\lib\emercoin::getFirstAddress();

var_dump($addr);

$signature = darkblog\lib\emercoin::signmessage($addr, ''.time());

var_dump($signature);

// $file = \darkblog\lib\emercoin::name_show("file:NeoWithGuns");
// $file = json_decode($file['value'], TRUE);

// ob_clean();

// header('Content-type: "'.$file['content_type'].'"');
// header('Content-Disposition: form-data; name="File";filename="'.$file['name'].'"');

// $file = \darkblog\lib\emercoin::name_show("file:NeoWithGuns:1", "base64");
// echo base64_decode($file['value']);

// $file = \darkblog\lib\emercoin::name_show("file:NeoWithGuns:2", "base64");
// echo base64_decode($file['value']);

// $file = \darkblog\lib\emercoin::name_show("file:NeoWithGuns:3", "base64");
// echo base64_decode($file['value']);

// $file = \darkblog\lib\emercoin::name_show("file:NeoWithGuns:4", "base64");
// echo base64_decode($file['value']);