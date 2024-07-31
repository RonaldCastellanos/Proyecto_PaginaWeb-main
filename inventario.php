<?php
session_start();
include 'db.php';
include 'header.php';

// Manejar la eliminación de productos
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM inventario WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: inventario.php");
}

// Manejar la adición y edición de productos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    if ($id) {
        // Actualizar producto
        $stmt = $pdo->prepare("UPDATE inventario SET nombre = :nombre, descripcion = :descripcion, cantidad = :cantidad, precio = :precio WHERE id = :id");
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion, 'cantidad' => $cantidad, 'precio' => $precio, 'id' => $id]);
    } else {
        // Agregar nuevo producto
        $stmt = $pdo->prepare("INSERT INTO inventario (nombre, descripcion, cantidad, precio) VALUES (:nombre, :descripcion, :cantidad, :precio)");
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion, 'cantidad' => $cantidad, 'precio' => $precio]);
    }

    header("Location: inventario.php");
}

// Obtener la lista de productos
$stmt = $pdo->query("SELECT * FROM inventario");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del producto a editar (si corresponde)
$editProduct = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM inventario WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content">
    <h2>Gestión de Inventario</h2>
    <button onclick="document.getElementById('addProductForm').style.display='block'">Agregar Producto</button>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['nombre']) ?></td>
                <td><?= htmlspecialchars($product['descripcion']) ?></td>
                <td><?= htmlspecialchars($product['cantidad']) ?></td>
                <td><?= htmlspecialchars($product['precio']) ?></td>
                <td>
                    <a href="inventario.php?edit=<?= $product['id'] ?>">Editar</a>
                    <a href="inventario.php?delete=<?= $product['id'] ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addProductForm" style="display:<?= $editProduct ? 'block' : 'none' ?>">
    <h2><?= $editProduct ? 'Editar Producto' : 'Agregar Producto' ?></h2>
    <form method="post" action="inventario.php">
        <input type="hidden" name="id" value="<?= $editProduct['id'] ?? '' ?>">
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($editProduct['nombre'] ?? '') ?>" required>
        <input type="text" name="descripcion" placeholder="Descripción" value="<?= htmlspecialchars($editProduct['descripcion'] ?? '') ?>" required>
        <input type="number" name="cantidad" placeholder="Cantidad" value="<?= htmlspecialchars($editProduct['cantidad'] ?? '') ?>" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" value="<?= htmlspecialchars($editProduct['precio'] ?? '') ?>" required>
        <button type="submit"><?= $editProduct ? 'Actualizar Producto' : 'Agregar Producto' ?></button>
    </form>
</div>

<?php include 'footer.php'; ?>
