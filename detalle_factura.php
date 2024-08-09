<?php
session_start();
include 'db.php';
include 'header.php';

// Depuración: Imprime el contenido de $_GET
var_dump($_GET);

// Verifica si el  'id' está presente en la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la información de la factura
    $stmt = $pdo->prepare("SELECT factura.id, cliente.nombre, cliente.apellido, factura.fecha, factura.total FROM factura JOIN cliente ON factura.cliente_id = cliente.id WHERE factura.id = :id");
    $stmt->execute(['id' => $id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontró la factura
    if ($factura) {
        // Obtener los detalles de la factura
        $stmt = $pdo->prepare("SELECT detalle_factura.cantidad, detalle_factura.precio_unitario, inventario.nombre FROM detalle_factura JOIN inventario ON detalle_factura.producto_id = inventario.id WHERE detalle_factura.factura_id = :id");
        $stmt->execute(['id' => $id]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="content">
            <h2>Detalle de Factura</h2>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($factura['nombre'] . ' ' . $factura['apellido']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($factura['fecha']) ?></p>
            <p><strong>Total:</strong> <?= htmlspecialchars($factura['total']) ?></p>

            <h3>Productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                        <td><?= htmlspecialchars($detalle['cantidad']) ?></td>
                        <td><?= htmlspecialchars($detalle['precio_unitario']) ?></td>
                        <td><?= htmlspecialchars($detalle['cantidad'] * $detalle['precio_unitario']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php
    } else {
        echo "<p>No se encontró la factura.</p>";
    }
} else {
    echo "<p>ID de factura no especificado o vacío.</p>";
}

include 'footer.php';
?>
