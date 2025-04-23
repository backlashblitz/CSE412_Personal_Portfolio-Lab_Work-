<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing portfolio data
$sql = "SELECT * FROM portfolios WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$portfolio = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $bio = $_POST['bio'];
    $soft_skills = $_POST['soft_skills'];
    $technical_skills = $_POST['technical_skills'];
    $bsc_cgpa = $_POST['bsc_cgpa'];
    $bsc_institute = $_POST['bsc_institute'];
    $bsc_degree = $_POST['bsc_degree'];
    $bsc_year = $_POST['bsc_year'];
    $msc_cgpa = $_POST['msc_cgpa'];
    $msc_institute = $_POST['msc_institute'];
    $msc_degree = $_POST['msc_degree'];
    $msc_year = $_POST['msc_year'];
    $experience = $_POST['experience'];
    $projects = $_POST['projects'];

    // Handle image upload
    $photo_path = $portfolio['photo'] ?? NULL;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['photo']['type'];
        if (in_array($file_type, $allowed_types)) {
            $photo_name = time() . '_' . basename($_FILES['photo']['name']);
            $photo_tmp = $_FILES['photo']['tmp_name'];
            $upload_dir = "uploads/";
            $photo_path = $upload_dir . $photo_name;
            move_uploaded_file($photo_tmp, $photo_path);
        } else {
            echo "<p class='error'>Invalid file format. Only JPG, JPEG, PNG allowed.</p>";
            exit();
        }
    }

    if ($portfolio) {
        // Update existing portfolio
        $sql = "UPDATE portfolios SET 
            name=?, contact=?, photo=?, bio=?, soft_skills=?, technical_skills=?, 
            bsc_cgpa=?, bsc_institute=?, bsc_degree=?, bsc_year=?, 
            msc_cgpa=?, msc_institute=?, msc_degree=?, msc_year=?, 
            experience=?, projects=? 
            WHERE user_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssi",
            $name, $contact, $photo_path, $bio, $soft_skills, $technical_skills,
            $bsc_cgpa, $bsc_institute, $bsc_degree, $bsc_year,
            $msc_cgpa, $msc_institute, $msc_degree, $msc_year,
            $experience, $projects, $user_id
        );
    } else {
        // Insert new portfolio
        $sql = "INSERT INTO portfolios 
            (user_id, name, contact, photo, bio, soft_skills, technical_skills, 
            bsc_cgpa, bsc_institute, bsc_degree, bsc_year, 
            msc_cgpa, msc_institute, msc_degree, msc_year, 
            experience, projects) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issssssssssssssss",
            $user_id, $name, $contact, $photo_path, $bio, $soft_skills, $technical_skills,
            $bsc_cgpa, $bsc_institute, $bsc_degree, $bsc_year,
            $msc_cgpa, $msc_institute, $msc_degree, $msc_year,
            $experience, $projects
        );
    }

    if ($stmt->execute()) {
        header("Location: portfolio_view.php");
        exit();
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Portfolio</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($portfolio['name'] ?? ''); ?>" required>
        <input type="text" name="contact" placeholder="Contact" value="<?php echo htmlspecialchars($portfolio['contact'] ?? ''); ?>" required>

        <label>Profile Photo:</label>
        <input type="file" name="photo">
        <?php if (!empty($portfolio['photo'])): ?>
            <img src="<?php echo htmlspecialchars($portfolio['photo']); ?>" alt="Profile Photo" width="100">
        <?php endif; ?>
        
        <h3>Bio</h3>
        <textarea name="bio" placeholder="Short Bio"><?php echo htmlspecialchars($portfolio['bio'] ?? ''); ?></textarea>

        <h3>Skills</h3>
        <input type="text" name="soft_skills" placeholder="Soft Skills" value="<?php echo htmlspecialchars($portfolio['soft_skills'] ?? ''); ?>">
        <input type="text" name="technical_skills" placeholder="Technical Skills" value="<?php echo htmlspecialchars($portfolio['technical_skills'] ?? ''); ?>">

        <h3>Education</h3>
        <input type="text" name="bsc_degree" placeholder="BSc Degree" value="<?php echo htmlspecialchars($portfolio['bsc_degree'] ?? ''); ?>">
        <input type="text" name="bsc_institute" placeholder="BSc Institute" value="<?php echo htmlspecialchars($portfolio['bsc_institute'] ?? ''); ?>">
        <input type="text" name="bsc_year" placeholder="BSc Year" value="<?php echo htmlspecialchars($portfolio['bsc_year'] ?? ''); ?>">
        <input type="text" name="bsc_cgpa" placeholder="BSc CGPA" value="<?php echo htmlspecialchars($portfolio['bsc_cgpa'] ?? ''); ?>">
        <input type="text" name="msc_degree" placeholder="MSc Degree" value="<?php echo htmlspecialchars($portfolio['msc_degree'] ?? ''); ?>">
        <input type="text" name="msc_institute" placeholder="MSc Institute" value="<?php echo htmlspecialchars($portfolio['msc_institute'] ?? ''); ?>">
        <input type="text" name="msc_year" placeholder="MSc Year" value="<?php echo htmlspecialchars($portfolio['msc_year'] ?? ''); ?>">
        <input type="text" name="msc_cgpa" placeholder="MSc CGPA" value="<?php echo htmlspecialchars($portfolio['msc_cgpa'] ?? ''); ?>">

        <h3>Experience & Projects</h3>
        <textarea name="experience"><?php echo htmlspecialchars($portfolio['experience'] ?? ''); ?></textarea>
        <textarea name="projects"><?php echo htmlspecialchars($portfolio['projects'] ?? ''); ?></textarea>

        <button type="submit" class="btn-submit">Save Portfolio</button>
    </form>
</div>
</body>
</html>
