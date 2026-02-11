<?php
include "auth_admin.php";  // Only admin can access
include "testdb.php";      // Database connection

// Check if 'id' is passed via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_dashboard.php?error=1");
    exit();
}

$id = intval($_GET['id']);  // Convert to integer for safety

// Prevent deleting yourself if you are an instructor
// (optional, depends if admin can delete other admins)
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // ID not found
    header("Location: admin_dashboard.php?error=2");
    exit();
}

$row = $result->fetch_assoc();
if ($row['role'] !== 'instructor') {
    // Only allow deletion of instructors
    header("Location: admin_dashboard.php?error=3");
    exit();
}

// Delete instructor
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin_dashboard.php?success=1");
    exit();
} else {
    echo "Error deleting instructor.";
}
