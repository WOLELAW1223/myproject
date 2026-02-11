
<?php include "auth_admin.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Welcome Admin <?php echo $_SESSION['firstName']; ?></h1>

<a href="create_instructor.php">➕ Create-Instructor</a> |
<a href="Logout.php">Logout</a>

<hr>
<h2>All Instructors</h2>

<?php
include "testdb.php";

// Fetch all instructors
$result = $conn->query("SELECT id, firstName, lastName, email, course FROM users WHERE role='instructor'");

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Action</th></tr>";
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['firstName']." ".$row['lastName']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>".$row['course']."</td>";
    echo "<td><a href='delete_instructor.php?id=".$row['id']."' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
</body>
</html>

