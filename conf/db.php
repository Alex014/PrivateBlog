<?php
if(!isset($application)) die("Can not call directly");

if(SQLITE) {
    $cwd = getcwd();
    if(substr($cwd, strlen($cwd) - 2, 2) == 'me')
            $cwd .= '/..';
    define('FILENAME', $cwd.'/database.db');

if(!file_exists(FILENAME)) {
    touch (FILENAME);
    chmod(FILENAME, 0666);
    
    $db = new SQLite3(FILENAME);
    
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `word` TEXT KEY
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX iword on `keywords` (word)";
    $db->exec($sql);    

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `keywords_langs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `keyword_id` INTEGER  NOT NULL,
  `lang_id` INTEGER  NOT NULL
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX klikeyword_id_lang_id on `keywords_langs` (`keyword_id`,`lang_id`)";
    $db->exec($sql);    
    
    $sql = "CREATE INDEX klikeyword_id on `keywords_langs` (`keyword_id`)";
    $db->exec($sql);    
    
    $sql = "CREATE INDEX klilang_id on `keywords_langs` (`lang_id`)";
    $db->exec($sql);    

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `langs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT DEFAULT NULL
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX langs_iname on `langs` (`name`)";
    $db->exec($sql);    

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `lang` TEXT DEFAULT NULL,
  `lang_id` INTEGER  DEFAULT NULL,
  `name` TEXT DEFAULT NULL,
  `username` TEXT DEFAULT NULL,
  `user_id` INTEGER  DEFAULT NULL,
  `sig` TEXT DEFAULT NULL,
  `title` TEXT DEFAULT NULL,
  `reply` TEXT DEFAULT NULL,
  `reply_id` INTEGER  DEFAULT NULL,
  `content` TEXT,
  `keywords` TEXT DEFAULT NULL,
  `metadata` text,
  `v` INTEGER DEFAULT '0'
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX posts_inameusername on `posts` (`name`,`username`)";
    $db->exec($sql);    
    
    $sql = "CREATE INDEX posts_ilang on `posts` (`lang`)";
    $db->exec($sql);    
    
    $sql = "CREATE INDEX posts_iname on `posts` (`name`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_iusername on `posts` (`username`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_iuser_id on `posts` (`user_id`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_isig on `posts` (`sig`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_ititle on `posts` (`title`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_ireply on `posts` (`reply`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_ireply_id on `posts` (`reply_id`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_ikeywords on `posts` (`keywords`)";
    $db->exec($sql);  
    
    $sql = "CREATE INDEX posts_ilang_id on `posts` (`lang_id`)";
    $db->exec($sql); 
    
    

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `posts_keywords` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `post_id` INTEGER  DEFAULT NULL,
  `keyword_id` INTEGER  DEFAULT NULL
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE INDEX posts_keywords_ipost_id on `posts_keywords` (`post_id`)";
    $db->exec($sql); 
    
    $sql = "CREATE INDEX posts_keywords_ikeyword_id on `posts_keywords` (`keyword_id`)";
    $db->exec($sql); 

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `session` (
  `user_ip` TEXT NOT NULL,
  `skey` TEXT NOT NULL,
  `data` BLOB,
  `_edited` INTEGER NOT NULL,
  `session_id` INTEGER DEFAULT NULL,
  `valid` TEXT DEFAULT NULL
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX sssiskey on `session` (`skey`)";
    $db->exec($sql);    
    
    $sql = "CREATE INDEX sssiuser_ip on `session` (`user_ip`)";
    $db->exec($sql);  

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `key` TEXT DEFAULT NULL,
  `username` TEXT DEFAULT NULL,
  `sig` TEXT DEFAULT NULL,
  `descr` text
);
SQL;
    $db->exec($sql);
    
    $sql = "CREATE UNIQUE INDEX uuuikey on `users` (`key`)";
    $db->exec($sql);    
    
    $sql = "CREATE UNIQUE INDEX uuuiusername on `users` (`username`)";
    $db->exec($sql);    
    
    $sql = "CREATE UNIQUE INDEX uuuisig on `users` (`sig`)";
    $db->exec($sql);    
    
}
else {
    $db = new SQLite3(FILENAME);
}
    \config::$db = $db;
    
    require(dirname(__FILE__).'/../other/session.php');
    $session = new session(config::$db, 'session');

    define('DB_PREFIX', '');

}
else {

    //mVT01xz6wZreFlt
    //Connecting
    $connection = new PDO("mysql:host=localhost;dbname=darkblog", 'root', 'root');
    \config::$db = $connection;
    
    $connection->query('SET NAMES "utf8"');
    //Starting DB-based session
    require(dirname(__FILE__).'/../other/session.php');
    $session = new session($connection, 'session');

    
    require(dirname(__FILE__).'/../other/meekrodb.class.php');
    DB::$user = 'root';
    DB::$password = 'root';
    DB::$dbName = 'darkblog';
    DB::$host = 'localhost'; //defaults to localhost if omitted
    DB::$port = '3306'; // defaults to 3306 if omitted
    DB::$encoding = 'utf8'; // defaults to latin1 if omitted

    define('DB_PREFIX', '');
}