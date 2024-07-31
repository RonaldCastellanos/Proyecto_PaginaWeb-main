<?php
function checkLogin($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE usuario = :username AND contrasena = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
