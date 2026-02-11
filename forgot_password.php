<?php
include "testdb.php"; // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        echo "Email is required";
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        // Generate reset token
        $token = bin2hex(random_bytes(16)); // 32-character token

        // Store token in password_resets table
        $stmt2 = $conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (?, ?)");
        $stmt2->bind_param("is", $userId, $token);
        $stmt2->execute();
        $stmt2->close();

        // Create reset link
        $resetLink = "http://localhost/myproject/Html/reset_password.php?token=" . $token;

        // For testing, we just echo the link (replace with mail() in real)
        echo "success: Reset link → <a href='$resetLink'>$resetLink</a>";

    } else {
        echo "Email not found";
    }

    $stmt->close();
}
?>

