<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'create') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $sql = "INSERT INTO clientes (nombre, apellido, telefono, correo, direccion, fecha_nacimiento)
            VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$direccion', '$fecha_nacimiento')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='message'>Cliente guardado exitosamente</div>";
    } else {
        echo "<div class='message'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            background: #50b3a2;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #45a091;
        }
        .message {
            background: #e8491d;
            color: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <div class="form-container">
        <h2>Ingresar Cliente</h2>
        <form action="ingresar_clientes.php" method="POST">
            <input type="hidden" name="action" value="create">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required>
            
            <button type="submit">Guardar Cliente</button>
        </form>
    </div>
</div>

</body>
</html>
