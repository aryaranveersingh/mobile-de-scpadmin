<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING  & ~E_DEPRECATED );
ini_set('max_execution_time', 0);
$app_path = 'data/';
$log_path = '';

$host = "127.0.0.1:8889";
$user = "root";
$pass = "root";
$database = "tmp";

// $host = "127.0.0.1";
// $user = "username";
// $pass = "password";
// $database = "database";
$mysql = mysql_connect($host,$user,$pass) or die("Error"); 
mysql_select_db($database, $mysql);

