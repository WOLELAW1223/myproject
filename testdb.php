<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "lab";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_errno) {
    die("❌ Connection failed: " . $conn->connect_error);
}

//echo "✅ Connected Successfully!";
?>
