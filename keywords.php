<?php
$application = 'darkblog';
require_once 'conf/conf.php';

$application = 'darkblog';
require_once 'autoloader.php';

$autoloader = new libAutoloader();

require_once 'conf/db.php';
require_once 'conf/emercoin.conf.php';
require_once 'conf/other.php';

$page = 'keywords';

$okeywords = new \darkblog\objects\keywords();

\darkblog\other\url::parse();

if(!empty($_GET['id'])) {
    $keyword_id = (int)$_GET['id'];
    $keyword = $okeywords->getKeyword($keyword_id);
    
    if($_GET['mode'] == 'bloggers') {
        if($_GET['order'] == 'username') \darkblog\db\pager::$order = 'username';
        elseif($_GET['order'] == 'total') \darkblog\db\pager::$order = 'posts DESC';

        if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];

        $ousers = new \darkblog\objects\users();
        $keyword['users'] = $ousers->getByKeyword($keyword_id);
    
        require 'templates/header.php';
        require 'templates/keyword_bloggers.php';
    }
    elseif($_GET['mode'] == 'keywords') {
        $keyword['keywords'] = $okeywords->getByKeyword($keyword_id);
    
        require 'templates/header.php';
        require 'templates/keyword_keywords.php';
    }
    else {
        \darkblog\db\pager::$order = 'name';
        if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];
        
        $oposts = new \darkblog\objects\posts();
        $keyword['posts'] = $oposts->getByKeyword($keyword_id);
    
        require 'templates/header.php';
        require 'templates/keyword.php';
    }
}
elseif(!empty($_GET['name'])) {
    $name = $_GET['name'];
    $keyword = $okeywords->getKeywordByName($name);
    
    if (array_key_exists('mode', $_GET)) {
        if($_GET['mode'] == 'bloggers') {
            if (!empty($_GET['order'])) {
                if($_GET['order'] == 'username') \darkblog\db\pager::$order = 'username';
                elseif($_GET['order'] == 'total') \darkblog\db\pager::$order = 'posts DESC';
            }

            if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];
            
            $ousers = new \darkblog\objects\users();
            $keyword['users'] = $ousers->getByKeyword($keyword['id']);
        
            require 'templates/header.php';
            require 'templates/keyword_bloggers.php';
        }
        elseif($_GET['mode'] == 'keywords') {
            $keyword['keywords'] = $okeywords->getByKeyword($keyword['id']);
        
            require 'templates/header.php';
            require 'templates/keyword_keywords.php';
        }
    } else {
        \darkblog\db\pager::$order = 'name';
        if(!empty($_GET['page'])) \darkblog\db\pager::$page = (int)$_GET['page'];
        
        $oposts = new \darkblog\objects\posts();
        $keyword['posts'] = $oposts->getByKeyword($keyword['id']);
    
        require 'templates/header.php';
        require 'templates/keyword.php';
    }
}
else {
    
    $keywords = $okeywords->getKeywords();

    require 'templates/header.php';
    require 'templates/keywords.php';
}

require 'templates/footer.php';