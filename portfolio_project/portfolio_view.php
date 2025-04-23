<?php
// Start session
session_start();

// Include database connection
include 'config/db.php';

// Fetch the latest portfolio
$user_id = $_SESSION['user_id']; 
$sql = "SELECT * FROM portfolios WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $portfolio = $result->fetch_assoc();
} else {
    die("<p class='error'>No portfolio found!</p>");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio View</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2><?php echo htmlspecialchars($portfolio['name']); ?>'s Portfolio</h2>
    
    <?php if (!empty($portfolio['photo'])): ?>
        <img src="<?php echo htmlspecialchars($portfolio['photo']); ?>" alt="Profile Photo" class="profile-photo">
    <?php endif; ?>

    <p class="contact-info">📞 CONTACT: <?php echo htmlspecialchars($portfolio['contact']); ?></p>
    <p class="bio">✍ BIO: <?php echo nl2br(htmlspecialchars($portfolio['bio'])); ?></p>

    <div class="skills">
        <h3>💡 SKILLS</h3>
        <p class="soft-skills">🔹 Soft Skills: <?php echo htmlspecialchars($portfolio['soft_skills']); ?></p>
        <p class="technical-skills">🔹 Technical Skills: <?php echo htmlspecialchars($portfolio['technical_skills']); ?></p>
    </div>

    <div class="education">
        <h3>🎓 EDUCATION</h3>
        <p><b>BSc:</b> <?php echo htmlspecialchars($portfolio['bsc_degree']); ?>, <?php echo htmlspecialchars($portfolio['bsc_institute']); ?> (<?php echo htmlspecialchars($portfolio['bsc_year']); ?>) - CGPA: <?php echo htmlspecialchars($portfolio['bsc_cgpa']); ?></p>
        <p><b>MSc:</b> <?php echo htmlspecialchars($portfolio['msc_degree']); ?>, <?php echo htmlspecialchars($portfolio['msc_institute']); ?> (<?php echo htmlspecialchars($portfolio['msc_year']); ?>) - CGPA: <?php echo htmlspecialchars($portfolio['msc_cgpa']); ?></p>
    </div>

    <div class="experience">
        <h3>💼 EXPERIENCE</h3>
        <p><?php echo nl2br(htmlspecialchars($portfolio['experience'])); ?></p>
    </div>

    <div class="projects">
        <h3>📁 PROJECTS</h3>
        <p><?php echo nl2br(htmlspecialchars($portfolio['projects'])); ?></p>
    </div>
    
    <div class="button-container">
    <a href="portfolio_form.php" class="btn-box btn-edit">Edit Portfolio</a>
    <a href="download_pdf.php" class="btn-box btn-download">Download Portfolio</a>
    <a href="logout.php" class="btn-box btn-logout">Logout</a>
    </div>

</div>
</body>
</html>
