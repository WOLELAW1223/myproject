<?php include "auth_instructor.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Welcome Instructor <?php echo $_SESSION['firstName']; ?></h1>

<a href="create_course.php">➕ Create Course</a> |
<a href="my_courses.php">📚 My Courses</a> |
<a href="add_lesson.php">📖 Add Lessons</a> |
<a href="students.php">👨‍🎓 Students</a> |
<a href="assignments.php">📝 Assignments</a> |
<a href="earnings.php">💰 Earnings</a> |
<a href="profile.php">👤 Profile</a> |
<a href="Logout.php">Logout</a>
</body>
</html>
