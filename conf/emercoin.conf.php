<?php
if(!isset($application)) die("Can not call directly");

if(PHAR) {
        //Config file
        $filename = $_SERVER['DOCUMENT_ROOT'].'/config.json';
        
        if(!file_exists($filename)) {
            echo "<center>";
            echo "<h1>ERROR!</h1>";
            echo "No file <b>$filename<b>";
            echo "<hr>";
            echo "Run <b>config.php<b> first";
            echo "</center>";
            die();
        }
            
        $content = file_get_contents($filename);
        $config = json_decode($content, true);
    
        darkblog\lib\emercoin::$username = $config['rpcuser'];
        darkblog\lib\emercoin::$password = $config['rpcpassword'];
        darkblog\lib\emercoin::$address = 'localhost';
        if(isset($config['rpcport']))
            darkblog\lib\emercoin::$port = $config['rpcport'];
        else
            darkblog\lib\emercoin::$port = '8332';
    
} else {
    darkblog\lib\emercoin::$username = 'user';
    darkblog\lib\emercoin::$password = 'hpe74xjkd';
    darkblog\lib\emercoin::$address = 'localhost';
    darkblog\lib\emercoin::$port = '8332';
}

darkblog\lib\emercoin::$comission = 0.1;