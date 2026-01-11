<?php
$host = 'localhost';
$db   = 'ems_db';     
$user = 'root';       
$pass = '';          

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");
?>