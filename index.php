<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
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
        header {
            background: #50b3a2;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            display: inline;
            padding: 0 20px 0 20px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container input[type="text"],
        .form-container input[type="password"],
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
        .user-list {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .user-list h2 {
            margin-top: 0;
        }
        .user-list p {
            border-bottom: 1px solid #ccc;
            padding: 10px;
        }
        .logout {
            float: right;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <a href="logout.php" class="logout">Cerrar Sesión</a>
    </div>
</header>

<?php include 'menu.php'; ?>

<div class="container">
    <?php
    // Procesar solicitudes
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $contrasena = isset($_POST['contrasena']) ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : '';
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $identidad = $_POST['identidad'];

        if ($action == "create") {
            $sql = "INSERT INTO usuarios (nombre, apellido, usuario, contrasena, correo, rol, genero, direccion, fecha_nacimiento, identidad)
                    VALUES ('$nombre', '$apellido', '$usuario', '$contrasena', '$correo', '$rol', '$genero', '$direccion', '$fecha_nacimiento', '$identidad')";
            $message = "Usuario creado exitosamente";
        } elseif ($action == "update") {
            if (!empty($contrasena)) {
                $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', usuario='$usuario', contrasena='$contrasena', correo='$correo', rol='$rol', genero='$genero', direccion='$direccion', fecha_nacimiento='$fecha_nacimiento', identidad='$identidad' WHERE id=$id";
            } else {
                $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', usuario='$usuario', correo='$correo', rol='$rol', genero='$genero', direccion='$direccion', fecha_nacimiento='$fecha_nacimiento', identidad='$identidad' WHERE id=$id";
            }
            $message = "Usuario actualizado exitosamente";
        } elseif ($action == "delete") {
            $sql = "DELETE FROM usuarios WHERE id=$id";
            $message = "Usuario eliminado exitosamente";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<div class='message'>$message</div>";
        } else {
            echo "<div class='message'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
    ?>

    <div class="form-container">
        <form action="" method="POST">
            <input type="hidden" name="id" value="">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="rol" placeholder="Rol" required>
            <input type="text" name="genero" placeholder="Género" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required>
            <input type="text" name="identidad" placeholder="Identidad" required>

            <button type="submit" name="action" value="create">Crear Usuario</button>
            <button type="submit" name="action" value="update">Actualizar Usuario</button>
            <button type="submit" name="action" value="delete">Eliminar Usuario</button>
        </form>
    </div>

    <div class="user-list">
        <?php
        // Mostrar usuarios existentes
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Lista de Usuarios</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>ID: " . $row["id"] . " - Nombre: " . $row["nombre"] . " " . $row["apellido"] . " - Usuario: " . $row["usuario"] . " - Correo: " . $row["correo"] . "</p>";
            }
        } else {
            echo "<p>No hay usuarios registrados.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
