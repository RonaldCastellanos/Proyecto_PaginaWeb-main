<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Crear (Insertar un nuevo registro)
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO inventario (nombre, descripcion, cantidad, precio) VALUES ('$nombre', '$descripcion', '$cantidad', '$precio')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Leer (Mostrar todos los registros)
$sql = "SELECT * FROM inventario";
$result = $conn->query($sql);
$inventario = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $inventario[] = $row;
    }
} else {
    echo "0 resultados";
}

// Actualizar (Modificar un registro existente)
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $sql = "UPDATE inventario SET nombre='$nombre', descripcion='$descripcion', cantidad='$cantidad', precio='$precio' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error actualizando el registro: " . $conn->error;
    }
}

// Eliminar (Borrar un registro)
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];

    $sql = "DELETE FROM inventario WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado exitosamente";
    } else {
        echo "Error eliminando el registro: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Inventario</title>
</head>
<body>
    <h1>Gestión de Inventario</h1>

    <!-- Formulario para crear un nuevo registro -->
    <h2>Crear nuevo registro</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="create">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <textarea name="descripcion" placeholder="Descripción"></textarea>
        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <input type="text" name="precio" placeholder="Precio" required>
        <button type="submit">Crear</button>
    </form>

    <!-- Mostrar todos los registros -->
    <h2>Inventario</h2>
    <?php if (!empty($inventario)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha Agregado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($inventario as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['nombre']; ?></td>
                    <td><?php echo $item['descripcion']; ?></td>
                    <td><?php echo $item['cantidad']; ?></td>
                    <td><?php echo $item['precio']; ?></td>
                    <td><?php echo $item['fecha_agregado']; ?></td>
                    <td>
                        <!-- Formulario para actualizar un registro -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="text" name="nombre" value="<?php echo $item['nombre']; ?>" required>
                            <textarea name="descripcion"><?php echo $item['descripcion']; ?></textarea>
                            <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" required>
                            <input type="text" name="precio" value="<?php echo $item['precio']; ?>" required>
                            <button type="submit">Actualizar</button>
                        </form>

                        <!-- Formulario para eliminar un registro -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No hay registros en el inventario.</p>
    <?php endif; ?>
</body>
</html>
