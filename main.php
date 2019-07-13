<?php
//define('PHAR', true);
//define('SQLITE', true);

if(empty($_SERVER['REQUEST_URI']) || ($_SERVER['REQUEST_URI'] == '/')) {
    require_once __DIR__.'/bloggers.php';
} elseif(($_SERVER['REQUEST_URI'] == '/me') || ($_SERVER['REQUEST_URI'] == '/me/')) {
    require_once __DIR__.'/me/new_post.php';
} elseif($_SERVER['REQUEST_URI'] == '/favicon.ico') {
    echo file_get_contents(__DIR__.'/favicon.ico');
} elseif(strpos ($_SERVER['REQUEST_URI'] , '/font/') !== false) {
    $rrr = explode('/', $_SERVER['REQUEST_URI']);
    $fff = explode('?', $rrr[count($rrr) - 1]);
    $fontname = $fff[0];
    @ob_end_clean();
    echo file_get_contents(__DIR__.'/css/font/'.$fontname);
} elseif(strpos ($_SERVER['REQUEST_URI'] , '/img/') !== false) {
    $rrr = explode('/', $_SERVER['REQUEST_URI']);
    $fff = explode('?', $rrr[count($rrr) - 1]);
    $imgname = $fff[0];
    @ob_end_clean();
    echo file_get_contents(__DIR__.'/img/'.$imgname);
} elseif(strpos ($_SERVER['REQUEST_URI'] , '.php') !== false) {
    require_once __DIR__.$_SERVER['REQUEST_URI'];
} else {
    @ob_end_clean();
    if(file_exists(__DIR__.$_SERVER['REQUEST_URI']))
        echo file_get_contents(__DIR__.$_SERVER['REQUEST_URI']);
}