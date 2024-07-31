<?php
session_start();
include 'db.php';
include 'header.php';

// Manejar la eliminación de clientes
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM cliente WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: clientes.php");
}

// Manejar la adición y edición de clientes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];

    if ($id) {
        // Actualizar cliente
        $stmt = $pdo->prepare("UPDATE cliente SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, direccion = :direccion, estado = :estado WHERE id = :id");
        $stmt->execute(['nombre' => $nombre, 'apellido' => $apellido, 'email' => $email, 'telefono' => $telefono, 'direccion' => $direccion, 'estado' => $estado, 'id' => $id]);
    } else {
        // Agregar nuevo cliente
        $stmt = $pdo->prepare("INSERT INTO cliente (nombre, apellido, email, telefono, direccion, estado) VALUES (:nombre, :apellido, :email, :telefono, :direccion, :estado)");
        $stmt->execute(['nombre' => $nombre, 'apellido' => $apellido, 'email' => $email, 'telefono' => $telefono, 'direccion' => $direccion, 'estado' => $estado]);
    }

    header("Location: clientes.php");
}

// Obtener la lista de clientes
$stmt = $pdo->query("SELECT * FROM cliente");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del cliente a editar (si corresponde)
$editClient = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $editClient = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content">
    <h2>Gestión de Clientes</h2>
    <button onclick="document.getElementById('addClientForm').style.display='block'">Agregar Cliente</button>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['nombre']) ?></td>
                <td><?= htmlspecialchars($client['apellido']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= htmlspecialchars($client['telefono']) ?></td>
                <td><?= htmlspecialchars($client['direccion']) ?></td>
                <td><?= htmlspecialchars($client['estado']) ?></td>
                <td>
                    <a href="clientes.php?edit=<?= $client['id'] ?>">Editar</a>
                    <a href="clientes.php?delete=<?= $client['id'] ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addClientForm" style="display:<?= $editClient ? 'block' : 'none' ?>">
    <h2><?= $editClient ? 'Editar Cliente' : 'Agregar Cliente' ?></h2>
    <form method="post" action="clientes.php">
        <input type="hidden" name="id" value="<?= $editClient['id'] ?? '' ?>">
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($editClient['nombre'] ?? '') ?>" required>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($editClient['apellido'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($editClient['email'] ?? '') ?>" required>
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($editClient['telefono'] ?? '') ?>">
        <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($editClient['direccion'] ?? '') ?>">
        <input type="text" name="estado" placeholder="Estado" value="<?= htmlspecialchars($editClient['estado'] ?? '') ?>">
        <button type="submit"><?= $editClient ? 'Actualizar Cliente' : 'Agregar Cliente' ?></button>
    </form>
</div>

<?php include 'footer.php'; ?>
