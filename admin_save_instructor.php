<?php
include "auth_admin.php";
include "testdb.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create_instructor.php");
    exit();
}

$firstName = trim($_POST['firstName']);
$lastName  = trim($_POST['lastName']);
$email     = trim($_POST['email']);
$password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
$course    = trim($_POST['course']);
$role      = "instructor";

// Check email
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Email already exists <br>";
    echo "<a href='create_instructor.php'>Back</a>";
    exit();
}

// Insert
$stmt = $conn->prepare(
    "INSERT INTO users (firstName, lastName, email, password, course, role)
     VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssssss",
    $firstName,
    $lastName,
    $email,
    $password,
    $course,
    $role
);

$stmt->execute();

header("Location: admin_dashboard.php?success=1");
exit();
