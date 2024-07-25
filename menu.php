<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .menu {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px;
            text-align: center;
        }
        .menu a {
            text-decoration: none;
            color: #50b3a2;
            font-size: 18px;
            margin: 10px;
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #50b3a2;
            border-radius: 4px;
        }
        .menu a:hover {
            background: #50b3a2;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="menu">
        <h2>Menú Principal</h2>
        <a href="crear_usuario.php">Crear Usuarios</a>
        <a href="ingresar_clientes.php">Ingresar Clientes</a>
        <a href="inventario.php">Inventario</a>
    </div>
</body>
</html>
