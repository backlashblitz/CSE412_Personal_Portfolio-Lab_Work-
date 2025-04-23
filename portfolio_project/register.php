<?php
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Email already exists.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="name" required placeholder="Full Name">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit" class="btn-box btn-register">Register</button>
        </form>
        <p><a href="login.php">Already have an account? Login</a></p>
    </div>
</body>
</html>

