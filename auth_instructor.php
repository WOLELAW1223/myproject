<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor'){
    header("Location: Login.php");
    exit();
}
?>
