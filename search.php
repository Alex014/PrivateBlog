<?php
$application = 'darkblog';
require_once 'conf/conf.php';

$application = 'darkblog';
require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$page = 'search';

$oposts = new \darkblog\objects\posts();

$posted = 'title';

\darkblog\other\url::parse();

\darkblog\db\pager::$order = 'name';
if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];

if(isset($_POST['title']) && !empty(trim($_POST['title']))) {
    $result = $oposts->searchPostsByTitle($_POST['title']);
    $posts = $result['posts'];
    $keywords = $result['keywords'];
    $posted = 'title';
}
elseif(isset($_POST['regexp']) && !empty(trim($_POST['regexp']))) {
    $result = $oposts->searchPostsByContent($_POST['regexp']);
    $posts = $result['posts'];
    $keywords = $result['keywords'];
    $posted = 'regexp';
}
elseif(isset($_POST['allwords']) && isset($_POST['anywords']) && !empty(trim($_POST['allwords'])) && !empty(trim($_POST['anywords']))) {
    $all_words = array();
    $any_words = array();
    
    foreach ($_POST['allwords'] as $word) {
        if(!empty($word)) $all_words[] = $word;
    }
    
    foreach ($_POST['anywords'] as $word) {
        if(!empty($word)) $any_words[] = $word;
    }
    
    $result = $oposts->searchPostsByWords($all_words, $any_words); 
    $posts = $result['posts'];
    $keywords = $result['keywords'];
    $posted = 'words';
}

function fvalue($name, $index = -1) {
    if(isset($_POST[$name]) && ($index > -1) && (isset($_POST[$name][$index])))
        return htmlentities ($_POST[$name][$index]);
    elseif(isset($_POST[$name]))
        return htmlentities ($_POST[$name]);
    else
        return '';
}

require 'templates/header.php';
require 'templates/search.php';
require 'templates/footer.php';