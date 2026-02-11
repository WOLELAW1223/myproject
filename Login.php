<?php
session_start();
include "testdb.php";

$error = "";

// Check form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Login'])) {
    $email    = trim($_POST['username']);
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, firstName, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Successful login
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['role']      = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] === 'instructor') {
                    header("Location: instructor_dashboard.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/stylelogin.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <p>Login to your smart learn account</p>

    <form method="POST" action="">
        <table>
            <tr>
                <td>Username (Email):</td>
                <td><input type="text" name="username" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>
                    <input type="password" name="password" id="password" required>
                </td>
            </tr>
        </table>
        <p style="color:red; text-align:center;">
            <?php if(!empty($error)) echo $error; ?>
        </p>
        <input type="submit" name="Login" value="Login">
    </form>
    <p>Don't have an account? <a href="Register.html">Register Here</a></p>
</div>
</body>
</html>



