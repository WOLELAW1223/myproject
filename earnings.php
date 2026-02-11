<?php
session_start();

// Protect page: only instructors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

include "testdb.php"; // database connection

$instructor_id = $_SESSION['user_id'];

// Fetch earnings per course
$sql = "
SELECT c.title AS course_title, 
       SUM(o.amount) AS total_earning, 
       COUNT(o.id) AS total_sales
FROM courses c
LEFT JOIN orders o ON c.id = o.course_id
WHERE c.instructor_id = ?
GROUP BY c.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate overall earnings
$total_earning_overall = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Earnings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>💰 My Earnings</h2>
<a href="instructor_dashboard.php">⬅ Dashboard</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Course</th>
        <th>Total Sales</th>
        <th>Total Earning</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $total_earning_overall += $row['total_earning']; ?>
            <tr>
                <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                <td><?php echo $row['total_sales']; ?></td>
                <td><?php echo '$' . number_format($row['total_earning'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No sales yet.</td>
        </tr>
    <?php endif; ?>
</table>

<br>
<h3>Total Earning Overall: <?php echo '$' . number_format($total_earning_overall, 2); ?></h3>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
