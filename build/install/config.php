<?php
for($i = 1; $i < $argc; $i++) {
    $arg = $argv[$i];
    $aArg = explode('=', $arg);
    if((count($aArg) == 2) && (strtolower($aArg[0]) == '-f')) {
        $filename = $aArg[1];
    }
}

if(empty($filename)) {
    echo "ERROR!\n";
    echo "Param -f=filename not set it must point to emercoin.conf file\n";
    die();
}

$conf_contents = <<<INI
rpcuser=rpcuser
rpcpassword=rpcpassword
rpcallowip=127.0.0.1
rpcport=8332
server=1
listen=1
INI;

if(!file_exists($filename)) {
    $dirname = dirname($filename);
    if(!file_exists($dirname)) {
            echo "Directory '$dirname' does not exist !\n";
            echo "Did you install Emercoin wallet ?";
            die();
    }
	
    touch($filename);
    file_put_contents($filename, $conf_contents);
    echo "File '$filename' created, restart Emercoin service !";
    die();
}

$content = file_get_contents($filename);

if(empty(trim($content))) {
    file_put_contents($filename, $conf_contents);
    $content = $conf_contents;
} else {
    $content = preg_replace("/(.+?)(#.+?)(\n)/is", '$1$3', $content);
}
    
$config = parse_ini_string($content);

$configfile = 'config.json';
if(!file_exists($configfile)) touch($configfile);
file_put_contents($configfile, json_encode($config));