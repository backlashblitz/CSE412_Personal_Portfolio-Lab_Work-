<?php
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    
    if ($stmt->execute()) {
        echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error updating password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST">
            <input type="email" name="email" required placeholder="Enter your email"><br>
            <input type="password" name="new_password" required placeholder="New Password"><br>
            <button type="submit">Change Password</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
