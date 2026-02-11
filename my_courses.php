<?php
// -------------------------------
// Start session & protect page
// -------------------------------
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: Login.php");
    exit;
}

// -------------------------------
// Database connection
// -------------------------------
$conn = new mysqli("localhost", "root", "", "your_database_name");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$instructor_id = $_SESSION['user_id'];

// -------------------------------
// Fetch instructor courses
// -------------------------------
$sql = "SELECT id, title, description, price, status, created_at 
        FROM courses 
        WHERE instructor_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $title, $description, $price, $status, $created_at);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Courses</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>📚 My Courses</h2>

<a href="create_course.php">➕ Create Course</a> |
<a href="instructor_dashboard.php">⬅ Dashboard</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Course Title</th>
        <th>Description</th>
        <th>Price</th>
        <th>Status</th>
        <th>Created Date</th>
        <th>Action</th>
    </tr>

<?php
$hasCourses = false; // flag to check if instructor has any courses

while ($stmt->fetch()) {
    $hasCourses = true;
    ?>
    <tr>
        <td><?php echo $id; ?></td>
        <td><?php echo htmlspecialchars($title); ?></td>
        <td><?php echo htmlspecialchars($description); ?></td>
        <td><?php echo ($price == 0) ? "Free" : $price; ?></td>
        <td>
            <?php
            if ($status == 'pending') echo "⏳ Pending";
            elseif ($status == 'approved') echo "✅ Approved";
            else echo "❌ Rejected";
            ?>
        </td>
        <td><?php echo $created_at; ?></td>
        <td>
            <a href="edit_course.php?id=<?php echo $id; ?>">Edit</a> |
            <a href="delete_course.php?id=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this course?');">
                Delete
            </a>
        </td>
    </tr>
<?php } ?>

<?php if (!$hasCourses) { ?>
    <tr>
        <td colspan="7">No courses created yet.</td>
    </tr>
<?php } ?>

</table>

</body>
</html>

<?php
// -------------------------------
// Close statement and connection
// -------------------------------
$stmt->close();
$conn->close();
?>
