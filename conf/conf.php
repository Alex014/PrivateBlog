<?php
if(!isset($application)) die("Can not call directly");

error_reporting(E_ALL - E_NOTICE - E_DEPRECATED);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8"); 
mb_regex_encoding('UTF-8');
