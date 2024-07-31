<?php
session_start();
include 'db.php';
include 'header.php';

// Obtener la lista de clientes
$stmt = $pdo->query("SELECT * FROM cliente");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la lista de productos
$stmt = $pdo->query("SELECT * FROM inventario");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manejar la eliminación de facturas
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM factura WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: facturas.php");
}

// Manejar la adición de facturas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];

    // Insertar la factura en la base de datos
    $stmt = $pdo->prepare("INSERT INTO factura (cliente_id, fecha, total) VALUES (:cliente_id, :fecha, :total)");
    $stmt->execute(['cliente_id' => $cliente_id, 'fecha' => $fecha, 'total' => $total]);

    header("Location: facturas.php");
}

// Obtener la lista de facturas
$stmt = $pdo->query("SELECT f.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido FROM factura f JOIN cliente c ON f.cliente_id = c.id");
$factura = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
    <h2>Gestión de Facturas</h2>
    <button onclick="document.getElementById('addFacturaForm').style.display='block'">Agregar Factura</button>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factura as $factura): ?>
            <tr>
                <td><?= htmlspecialchars($factura['cliente_nombre'] . ' ' . $factura['cliente_apellido']) ?></td>
                <td><?= htmlspecialchars($factura['fecha']) ?></td>
                <td><?= htmlspecialchars($factura['total']) ?></td>
                <td>
                    <a href="facturas.php?edit=<?= $factura['id'] ?>">Editar</a>
                    <a href="facturas.php?delete=<?= $factura['id'] ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="addFacturaForm" style="display: none;">
    <h2>Agregar Factura</h2>
    <form method="post" action="facturas.php">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
            <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required>
        <label for="total">Total:</label>
        <input type="number" step="0.01" name="total" required>
        <button type="submit">Agregar Factura</button>
    </form>
    <button class="modal-close" onclick="document.getElementById('addFacturaForm').style.display='none'">Cerrar</button>
</div>

<?php include 'footer.php'; ?>
