<?php
session_start();
include 'db.php';
include 'header.php';

// Manejar la eliminación de usuarios
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: users.php");
}

// Manejar la adición y edición de usuarios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $genero = $_POST['genero'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $identidad = $_POST['identidad'];

    if ($id) {
        // Actualizar usuario
        if ($contrasena) {
            $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, usuario = :usuario, contrasena = :contrasena, correo = :correo, rol = :rol, genero = :genero, telefono = :telefono, direccion = :direccion, fecha_nacimiento = :fecha_nacimiento, identidad = :identidad WHERE id = :id");
            $stmt->execute(['nombre' => $nombre, 'apellido' => $apellido, 'usuario' => $usuario, 'contrasena' => $contrasena, 'correo' => $correo, 'rol' => $rol, 'genero' => $genero, 'telefono' => $telefono, 'direccion' => $direccion, 'fecha_nacimiento' => $fecha_nacimiento, 'identidad' => $identidad, 'id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, usuario = :usuario, correo = :correo, rol = :rol, genero = :genero, telefono = :telefono, direccion = :direccion, fecha_nacimiento = :fecha_nacimiento, identidad = :identidad WHERE id = :id");
            $stmt->execute(['nombre' => $nombre, 'apellido' => $apellido, 'usuario' => $usuario, 'correo' => $correo, 'rol' => $rol, 'genero' => $genero, 'telefono' => $telefono, 'direccion' => $direccion, 'fecha_nacimiento' => $fecha_nacimiento, 'identidad' => $identidad, 'id' => $id]);
        }
    } else {
        // Agregar nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO users (nombre, apellido, usuario, contrasena, correo, rol, genero, telefono, direccion, fecha_nacimiento, identidad) VALUES (:nombre, :apellido, :usuario, :contrasena, :correo, :rol, :genero, :telefono, :direccion, :fecha_nacimiento, :identidad)");
        $stmt->execute(['nombre' => $nombre, 'apellido' => $apellido, 'usuario' => $usuario, 'contrasena' => $contrasena, 'correo' => $correo, 'rol' => $rol, 'genero' => $genero, 'telefono' => $telefono, 'direccion' => $direccion, 'fecha_nacimiento' => $fecha_nacimiento, 'identidad' => $identidad]);
    }

    header("Location: users.php");
}

// Obtener la lista de usuarios
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del usuario a editar (si corresponde)
$editUser = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content">
    <h2>Gestión de Usuarios</h2>
    <button onclick="document.getElementById('addUserForm').style.display='block'">Agregar Usuario</button>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['nombre']) ?></td>
                <td><?= htmlspecialchars($user['apellido']) ?></td>
                <td><?= htmlspecialchars($user['usuario']) ?></td>
                <td><?= htmlspecialchars($user['correo']) ?></td>
                <td><?= htmlspecialchars($user['rol']) ?></td>
                <td>
                    <a href="users.php?edit=<?= $user['id'] ?>">Editar</a>
                    <a href="users.php?delete=<?= $user['id'] ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addUserForm" style="display:<?= $editUser ? 'block' : 'none' ?>">
    <h2><?= $editUser ? 'Editar Usuario' : 'Agregar Usuario' ?></h2>
    <form method="post" action="users.php">
        <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($editUser['nombre'] ?? '') ?>" required>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($editUser['apellido'] ?? '') ?>" required>
        <input type="text" name="usuario" placeholder="Usuario" value="<?= htmlspecialchars($editUser['usuario'] ?? '') ?>" required>
        <input type="password" name="contrasena" placeholder="Contraseña" <?= $editUser ? '' : 'required' ?>>
        <input type="email" name="correo" placeholder="Correo" value="<?= htmlspecialchars($editUser['correo'] ?? '') ?>" required>
        <input type="text" name="rol" placeholder="Rol" value="<?= htmlspecialchars($editUser['rol'] ?? '') ?>" required>
        <input type="text" name="genero" placeholder="Género" value="<?= htmlspecialchars($editUser['genero'] ?? '') ?>">
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($editUser['telefono'] ?? '') ?>">
        <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($editUser['direccion'] ?? '') ?>">
        <input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" value="<?= htmlspecialchars($editUser['fecha_nacimiento'] ?? '') ?>">
        <input type="text" name="identidad" placeholder="Identidad" value="<?= htmlspecialchars($editUser['identidad'] ?? '') ?>">
        <button type="submit"><?= $editUser ? 'Actualizar Usuario' : 'Agregar Usuario' ?></button>
    </form>
</div>

<?php include 'footer.php'; ?>
