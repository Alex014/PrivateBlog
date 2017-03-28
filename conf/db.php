<?php
if(!isset($application)) die("Can not call directly");
//mVT01xz6wZreFlt
//Connecting
$connection = new PDO("mysql:host=localhost;dbname=darkblog", 'root', 'root');
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