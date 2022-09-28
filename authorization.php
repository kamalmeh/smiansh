<?php
$servername = "localhost";
$port       = 3306;
$username   = "smianshc_smiansh";
$password   = "123@smiansh";
$database   = "smianshc_MainDb";
// phpinfo();

// Create connection
// $db = mysqli_connect($servername, $username, $password,$database,$port);

$db = new mysqli($servername, $username, $password, $database, $port);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
// echo "Connected successfully";