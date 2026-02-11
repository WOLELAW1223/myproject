<?php
// Start session
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/Logout.css">
</head>
<body>
    <div class="message">
        <h2>You have been logged out successfully.</h2>
        <a href="Login.html">Login Again</a>
    </div>
</body>
</html>
