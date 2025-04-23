<?php
// Start session
session_start();
include 'config/db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
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
    $photo_path = NULL;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['photo']['type'];
        if (in_array($file_type, $allowed_types)) {
            $photo_name = time() . '_' . basename($_FILES['photo']['name']); // Avoid duplicate file names
            $photo_tmp = $_FILES['photo']['tmp_name'];
            $upload_dir = "uploads/";
            $photo_path = $upload_dir . $photo_name;
            move_uploaded_file($photo_tmp, $photo_path);
        } else {
            echo "<p class='error'>Invalid file format. Only JPG, JPEG, PNG allowed.</p>";
            exit();
        }
    }

    // Insert data into the database
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

    if ($stmt->execute()) {
        header("Location: portfolio_view.php");
        exit();
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Portfolio</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="contact" placeholder="Contact" required>

        <label>Profile Photo:</label>
        <input type="file" name="photo">
        
        <h3>Bio</h3>
        <textarea name="bio" placeholder="Short Bio"></textarea>

        <h3>Skills</h3>
        <input type="text" name="soft_skills" placeholder="Soft Skills">
        <input type="text" name="technical_skills" placeholder="Technical Skills">

        <h3>Education</h3>
        <input type="text" name="bsc_degree" placeholder="BSc Degree">
        <input type="text" name="bsc_institute" placeholder="BSc Institute">
        <input type="text" name="bsc_year" placeholder="BSc Year">
        <input type="text" name="bsc_cgpa" placeholder="BSc CGPA">
        <input type="text" name="msc_degree" placeholder="MSc Degree">
        <input type="text" name="msc_institute" placeholder="MSc Institute">
        <input type="text" name="msc_year" placeholder="MSc Year">
        <input type="text" name="msc_cgpa" placeholder="MSc CGPA">

        <h3>Experience & Projects</h3>
        <textarea name="experience" placeholder="Your Experience"></textarea>
        <textarea name="projects" placeholder="Your Projects"></textarea>

        <button type="submit" class="btn-submit">Submit Portfolio</button>
    </form>
</div>
</body>
</html>
