<?php 
ob_start();
session_start();
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shughal2';

$timezone = date_default_timezone_set("Asia/Karachi");

//make connection with database
$con = new PDO("mysql:host=$servername;dbname=$dbname;",$username,$password);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>