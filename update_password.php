<?php
session_start(); // Start session

// Make sure user is logged in
if(!isset($_SESSION['user_id'])) {
    die("<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>Please log in first.</div>");
}

// Database connection
$host = "localhost";
$user = "root";       // DB username
$pass = "";           // DB password
$db   = "lab";        // Use your existing database 'lab'

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>Connection failed: " . $conn->connect_error . "</div>");
}

// Check form submission
if(isset($_POST['update'])) {
    $old_password     = $_POST['old_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Fetch current password hash from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows === 0) {
        echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>User not found!</div>";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->bind_result($current_hash);
    $stmt->fetch();

    // Verify old password
    if(!password_verify($old_password, $current_hash)) {
        echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>Old password is incorrect!</div>";
        $stmt->close();
        $conn->close();
        exit;
    }

    // Check if new passwords match
    if($new_password !== $confirm_password) {
        echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>New passwords do not match!</div>";
        $stmt->close();
        $conn->close();
        exit;
    }

    // Optional: enforce minimum password length
    if(strlen($new_password) < 6) {
        echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>Password must be at least 6 characters long!</div>";
        $stmt->close();
        $conn->close();
        exit;
    }

    // Hash new password and update
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $update->bind_param("si", $new_hash, $user_id);

    if($update->execute()) {
        echo "<div style='text-align:center;margin-top:50px;color:green;font-weight:bold;'>Password updated successfully!</div>";
    } else {
        echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>Error updating password.</div>";
    }

    $update->close();
    $stmt->close();
}

$conn->close();
?>
