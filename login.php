<?php
session_start();
include 'db.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = checkLogin($pdo, $username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
  
    <div class="login-container">
        <form class="login-form" action="login.php" method="POST">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <!-- Mensaje de error -->
            <?php if(isset($error)) { echo "<p>$error</p>"; } ?>
            <!-- Enlace para olvidar contraseña -->
            <a href="#">Forgot Password?</a>
        </form>
    </div>
</body>
</html>

