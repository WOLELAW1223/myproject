<?php
include "auth_admin.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Instructor</title>


</head>
<body>

<h1>Create Instructor</h1>

<form method="POST" action="admin_save_instructor.php">
    <input type="text" name="firstName" placeholder="First Name" required><br><br>
    <input type="text" name="lastName" placeholder="Last Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" name="course" placeholder="Course" required><br><br>

    <input type="submit" name="create" value="Create Instructor">
</form>

<a href="admin_dashboard.php">⬅ Back</a>

</body>
</html>
