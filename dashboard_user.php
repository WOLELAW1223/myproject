
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

include('testdb.php');

$user_id = $_SESSION['user_id'];
$sql = "SELECT firstName, profile_image FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$firstName = $row['firstName'];

// Determine profile image or letter avatar
if (!empty($row['profile_image']) && file_exists("uploads/" . $row['profile_image'])) {
    $profile_image = $row['profile_image'];
    $use_letter_avatar = false;
    $first_letter = '';
} else {
    $profile_image = null;
    $use_letter_avatar = true;
    $first_letter = strtoupper($firstName[0]); // First letter of first name
}

// Handle profile photo upload
if (isset($_POST['upload_photo'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp  = $_FILES['profile_image']['tmp_name'];
        $file_size = $_FILES['profile_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif',"bmp","svg+xml"];

        if (in_array($file_ext, $allowed) && $file_size <= 2*1024*1024) {
            $new_name = "profile_" . $user_id . "." . $file_ext;
            $upload_dir = "uploads/";
            $target_file = $upload_dir . $new_name;
            if (move_uploaded_file($file_tmp, $target_file)) {
                $sql = "UPDATE users SET profile_image = '$new_name' WHERE id = $user_id";
                mysqli_query($conn, $sql);
                $_SESSION['profile_image'] = $new_name;
                header("Location: dashboard_user.php");
                exit();
            } else { $error = "Upload failed"; }
        } else { $error = "Invalid file type or size > 2MB"; }
    } else { $error = "No file uploaded"; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
/* --- Reset & Body --- */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
body { background-color: #f4f4f9; }

/* --- Header --- */
.header {
    background-color: #1e3a8a;
    color: white;
    font-size: 22px;
    font-weight: bold;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
}
.header .logo { font-weight: bold; font-size: 20px; }

/* --- Navigation --- */
nav { display: flex; align-items: center; gap: 12px; }
nav a, .dropbtn {
    color: white; text-decoration: none; padding: 5px 10px;
    border-radius: 4px; cursor: pointer; transition: background 0.3s;
    font-weight: 500; font-size: 14px;
}
nav a:hover, .dropbtn:hover { background-color: #3b82f6; }

/* --- Dropdown --- */
.dropdown { position: relative; }
.dropbtn {
    display: flex; align-items: center; gap: 6px;
}
.dropbtn img, .letter-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #2563eb;
}
.dropdown-content {
    display: none; position: absolute; background-color: #3b82f6;
    min-width: 180px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    border-radius: 5px; z-index: 10; top: 100%; right: 0; padding: 10px;
}
.dropdown-content a { color: white; padding: 8px 12px; display: block; text-decoration: none; font-size: 14px; }
.dropdown-content a:hover { background-color: #1e40af; }
.dropdown-content form { display: flex; flex-direction: column; gap: 5px; margin:5px 0; }
.dropdown-content input[type="file"] { font-size: 12px; }
.dropdown-content button {
    background-color: #2563eb; color: white; border: none; padding: 6px;
    border-radius: 4px; cursor: pointer; font-size: 13px;
}
.dropdown-content button:hover { background-color: #3b82f6; }
.dropdown-content.show { display: block; }

/* --- Welcome Section --- */
.Welcome { padding: 20px; text-align: center; }
.Welcome h2 { margin-bottom: 8px; }
.Welcome p { font-size: 16px; color: #333; }

/* --- Course Cards --- */
.course-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; }
.card {
    background-color: white; border-radius: 10px; padding: 15px;
    width: 250px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center; transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
.card h3 { margin-bottom: 6px; } .card p { margin-bottom: 10px; color: #555; }
.card button { padding: 8px 15px; border: none; border-radius: 5px; background-color: #2563eb; color: white; cursor: pointer; transition: background 0.3s; }
.card button:hover { background-color: #3b82f6; }
.card button a { color: white; text-decoration: none; }

/* --- Responsive --- */
@media screen and (max-width: 768px) {
    .header { flex-direction: column; align-items: flex-start; gap: 8px; }
    nav { flex-direction: column; width: 100%; }
    .dropdown-content { right: auto; }
    .course-container { flex-direction: column; align-items: center; }
}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="logo">Smart Learn E-learning Dashboard</div>
    <nav>
        <a href="Coursepage.html">Courses</a>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <span class="dropbtn" onclick="toggleDropdown(this)">
                <?php if ($use_letter_avatar): ?>
                    <div class="letter-avatar"><?php echo $first_letter; ?></div>
                <?php else: ?>
                    <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile">
                <?php endif; ?>
                &#9662; <!-- Only dropdown arrow -->
            </span>
            <div class="dropdown-content">
                <a href="update_password.html">Change Password</a>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*">
                    <button type="submit" name="upload_photo">Upload Photo</button>
                </form>
                <a href="Logout.php">Logout</a>
            </div>
        </div>
    </nav>
</div>

<!-- Welcome Section -->
<div class="Welcome">
    <h2>Welcome Back, <?php echo htmlspecialchars($firstName); ?>!</h2>
    <p>Continue your learning journey. Explore new courses and track your progress.</p>
</div>

<!-- Course Cards -->
<div class="course-container">
    <div class="card">
        <h3>Web Development</h3>
        <p>HTML, CSS, JavaScript</p>
        <button><a href="webdevelopment vedio.html">Start Learning</a></button>
    </div>
    <div class="card">
        <h3>Programming</h3>
        <p>Python, Java, C++</p>
        <button><a href="Programing vedio.html">Start Learning</a></button>
    </div>
    <div class="card">
        <h3>Database</h3>
        <p>MySQL, SQL queries, DB design</p>
        <button><a href="Data base vedio.html">Start Learning</a></button>
    </div>
    <div class="card">
        <h3>Networking</h3>
        <p>Wireless Networking, Network Security</p>
        <button><a href="Networking Vedio.html">Start Learning</a></button>
    </div>
</div>

<!-- JavaScript -->
<script>
function toggleDropdown(element) {
    const dropdownContent = element.nextElementSibling;
    dropdownContent.classList.toggle('show');
}
window.onclick = function(event) {
    if (!event.target.closest('.dropbtn')) {
        document.querySelectorAll('.dropdown-content').forEach(function(dropdown) {
            dropdown.classList.remove('show');
        });
    }
}
</script>

</body>
</html>

