<?php
if(!isset($application)) die("Can not call directly");

\darkblog\db\pager::$interval = 2;

if(isset($_SESSION['records'])) 
    \darkblog\db\pager::$per_page = $_SESSION['records'];
else
    \darkblog\db\pager::$per_page = 10;

require_once __DIR__ .'/config.php';