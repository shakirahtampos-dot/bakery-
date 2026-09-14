<?php
$host = "localhost";
$user = "root";   // change if needed
$pass = "";       // change if you set a password
$dbname = "amor";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>