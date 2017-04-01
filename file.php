<?php
$application = 'darkblog';
require_once 'conf/conf.php';

require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$id = $_GET['id'];

$file = \darkblog\lib\emercoin::name_show('file:'.$id);
$file = json_decode($file['value'], TRUE);

if(empty($file)) die('No file found');

ob_clean();

header('Content-type: "'.$file['content_type'].'"');
header('Content-Disposition: form-data; name="File";filename="'.$file['name'].'"');

for($i = 1; $i <= $file['parts']; $i++) {
    $file_sub = \darkblog\lib\emercoin::name_show('file:'.$id.':'.$i, 'base64');
    echo base64_decode($file_sub['value']);
}