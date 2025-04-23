<?php
require 'config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: portfolio_form.php"); // Redirect to portfolio form
        exit();
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" required placeholder="Enter Email">
            <input type="password" name="password" required placeholder="Enter Password">
            <button type="submit" name="login" class="btn-box btn-login">Login</button>
        </form>
        <p><a href="register.php">Don't have an account? Register</a></p>
        
    </div>
</body>

</html>
