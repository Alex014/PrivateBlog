<?php
if(!isset($application)) die("Can not call directly");

if(PHAR) {
    /*$cwd = getcwd();
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
    darkblog\lib\emercoin::$port = $config['port'];*/
    
    $uid = posix_getuid();
    $shell_user = posix_getpwuid($uid);
    $homedir = $shell_user['dir']; 
    
    if(PHP_OS == 'Linux') {

        $filename = $homedir.'/.emercoin/emercoin.conf';
        
        if(!file_exists($filename)) {
            touch($filename);
            file_put_contents($filename, "rpcuser=rpcuser
rpcpassword=rpcpassword
rpcallowip=127.0.0.1
rpcport=8332");
            echo "File '$filename' created, restart Emercoin service !";
            die();
        }

        $content = file_get_contents($filename);
        $content = preg_replace("/(.+?)(#.+?)(\n)/is", '$1$3', $content);
        $config = parse_ini_string($content);

    }
    elseif(PHP_OS == 'Windows') {

        $filename = $homedir.'\AppData\EmerCoin\emercoin.conf';
        
        if(!file_exists($filename))
            $filename = $homedir.'\Application Data\EmerCoin\emercoin.conf';
        
        if(!file_exists($filename)) {
            $filename = $homedir.'\AppData\EmerCoin\emercoin.conf';
            touch($filename);
            file_put_contents($filename, "rpcuser=rpcuser
rpcpassword=rpcpassword
rpcallowip=127.0.0.1
rpcport=8332");
            echo "File '$filename' created, restart Emercoin service !";
            die();
        }
            

        $content = file_get_contents($filename);
        $content = preg_replace("/(.+?)(#.+?)(\n)/is", '$1$3', $content);
        $config = parse_ini_string($content);
    }
    else {
        echo 'Your OS ('.PHP_OS.') is not supported';
        die();
    }
    
    darkblog\lib\emercoin::$username = $config['rpcuser'];
    darkblog\lib\emercoin::$password = $config['rpcpassword'];
    darkblog\lib\emercoin::$address = 'localhost';
    if(isset($config['rpcport']))
        darkblog\lib\emercoin::$port = $config['rpcport'];
    else
        darkblog\lib\emercoin::$port = '8332';
    
}
else {
    darkblog\lib\emercoin::$username = 'user';
    darkblog\lib\emercoin::$password = 'hpe74xjkd';
    darkblog\lib\emercoin::$address = 'localhost';
    darkblog\lib\emercoin::$port = '8332';
}

darkblog\lib\emercoin::$comission = 0.1;