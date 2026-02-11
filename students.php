<?php
session_start();

// Protect page: only instructors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

include "testdb.php"; // database connection

$instructor_id = $_SESSION['user_id'];

// Fetch students enrolled in courses taught by this instructor
$sql = "
SELECT u.id AS student_id, u.firstName, u.lastName, u.email, c.title AS course_title, e.enrolled_at
FROM enrollments e
JOIN users u ON e.student_id = u.id
JOIN courses c ON e.course_id = c.id
WHERE c.instructor_id = ?
ORDER BY e.enrolled_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students Enrolled</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>👨‍🎓 Students Enrolled in My Courses</h2>
<a href="instructor_dashboard.php">⬅ Dashboard</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Enrolled At</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                <td><?php echo $row['enrolled_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No students enrolled yet.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
