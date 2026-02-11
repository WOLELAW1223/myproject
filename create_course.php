<?php
include "auth_instructor.php";   // protect page (only instructor)
include "testdb.php";            // database connection

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);
    $instructorId = $_SESSION['user_id']; // logged-in instructor ID

    if (empty($title) || empty($description)) {
        $message = "❌ Title and Description are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO courses (title, description, price, instructor_id, status)
             VALUES (?, ?, ?, ?, 'pending')"
        );
        $stmt->bind_param("ssdi", $title, $description, $price, $instructorId);

        if ($stmt->execute()) {
            $message = "✅ Course created successfully! Waiting for admin approval.";
        } else {
            $message = "❌ Error creating course.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Create New Course</h2>

<?php if ($message) echo "<p>$message</p>"; ?>

<form method="POST">
    <label>Course Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="5" required></textarea><br><br>

    <label>Price (0 = Free)</label><br>
    <input type="number" name="price" step="0.01" value="0"><br><br>

    <button type="submit">Create Course</button>
</form>

<br>
<a href="instructor_dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
