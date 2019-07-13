<?php
if(!isset($application)) die("Can not call directly");

if(PHAR) {
    $cwd = getcwd();
    if(substr($cwd, strlen($cwd) - 2, 2) == 'me')
        $cwd .= '/..';
    
    $configfile = $cwd.'/connection.json';
    
    if(!file_exists($configfile)) {
        throw new Exception("No config file '$configfile'");
    }
    
    $config = file_get_contents($configfile);
    $config = json_decode($config, true);
    
    darkblog\lib\emercoin::$username = $config['username'];
    darkblog\lib\emercoin::$password = $config['password'];
    darkblog\lib\emercoin::$address = $config['address'];
    darkblog\lib\emercoin::$port = $config['port'];
}
else {
    darkblog\lib\emercoin::$username = 'user';
    darkblog\lib\emercoin::$password = 'hpe74xjkd';
    darkblog\lib\emercoin::$address = 'localhost';
    darkblog\lib\emercoin::$port = '8332';
}

darkblog\lib\emercoin::$comission = 0.1;