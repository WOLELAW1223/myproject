<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "lab";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $phone     = trim($_POST['phone']);
    $email     = trim($_POST['email']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birth     = $_POST['birth'];
    $gender    = $_POST['gender'];
    $course    = $_POST['course'];
    $role      = "user";

    // Check duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email already exists.");
    }

    // Insert user
    $stmt = $conn->prepare(
        "INSERT INTO users (firstName, lastName, phone, email, password, birth, gender, course, role)
         VALUES (?,?,?,?,?,?,?,?,?)"
    );

    $stmt->bind_param(
        "sssssssss",
        $firstName,
        $lastName,
        $phone,
        $email,
        $password,
        $birth,
        $gender,
        $course,
        $role
    );

    if ($stmt->execute()) {
        echo "<h2>Registration Successful!</h2>";
        echo "<p>Welcome, $firstName $lastName</p>";
        echo "<a href='Login.html'>Login Here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

