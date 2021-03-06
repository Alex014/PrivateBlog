<?php
for($i = 1; $i < $argc; $i++) {
    $arg = $argv[$i];
    $aArg = explode('=', $arg);
    if((count($aArg) == 2) && (strtolower($aArg[0]) == '-f')) {
        $filename = $aArg[1];
    }
}

if(empty($filename)) {
    echo "\nmERROR!\n";
    echo "Param -f=filename not set it must point to emercoin.conf file\n";
    die();
}

if(!file_exists($filename)) {
    touch($filename);
    file_put_contents($filename, "rpcuser=rpcuser
rpcpassword=rpcpassword
rpcallowip=127.0.0.1
rpcport=8332");
    echo "File $filename created, restart Emercoin service !";
    die();
}


$content = file_get_contents($filename);
$content = preg_replace("/(.+?)(#.+?)(\n)/is", '$1$3', $content);
$config = parse_ini_string($content);

$configfile = 'config.json';
if(!file_exists($configfile)) touch($configfile);
file_put_contents($configfile, json_encode($config));