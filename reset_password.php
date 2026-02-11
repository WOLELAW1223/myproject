<?php
include "testdb.php";

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];

    if (empty($newPassword)) {
        $error = "Password cannot be empty.";
    } else {
        // Check token
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND used = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $userId = $row['user_id'];

            // Update user password
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt2->bind_param("si", $hashed, $userId);
            $stmt2->execute();
            $stmt2->close();

            // Mark token as used
            $stmt3 = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt3->bind_param("s", $token);
            $stmt3->execute();
            $stmt3->close();

            echo "Password reset successful! You can now <a href='login.html'>login</a>.";
            exit;
        } else {
            $error = "Invalid or expired token.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <label>New Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Reset Password</button>
</form>

</body>
</html>
