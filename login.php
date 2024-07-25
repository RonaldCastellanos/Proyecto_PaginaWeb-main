<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Verifica si la conexión está establecida
    if ($conn) {
        $sql = "SELECT * FROM users WHERE usuario='$usuario'";

        // Ejecuta la consulta
        if ($result = $conn->query($sql)) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($contrasena, $row['contraseña'])) {
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "Usuario no encontrado.";
            }
        } else {
            $error = "Error en la consulta: " . $conn->error;
        }
    } else {
        $error = "No se pudo conectar a la base de datos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container button {
            background: #50b3a2;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #45a091;
        }
        .login-container .error {
            color: #e8491d;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="" method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>

</body>
</html>

