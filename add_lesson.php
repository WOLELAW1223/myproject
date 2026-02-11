<?php
session_start();

// Protect page: only instructors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

include "testdb.php"; // Database connection

$instructor_id = $_SESSION['user_id'];
$message = "";

// ----------------------------
// Fetch courses for this instructor
// ----------------------------
$stmt = $conn->prepare("SELECT id, title FROM courses WHERE instructor_id = ?");
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// ----------------------------
// Handle form submission
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($course_id) || empty($title) || empty($content)) {
        $message = "❌ All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO lessons (course_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $course_id, $title, $content);

        if ($stmt->execute()) {
            $message = "✅ Lesson added successfully!";
        } else {
            $message = "❌ Error adding lesson: " . $stmt->error;
        }
    }
}
?>

<?php
$stmt->close();
$conn->close();
?>
