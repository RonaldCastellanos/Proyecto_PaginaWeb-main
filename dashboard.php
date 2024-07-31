<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';
?>
<div class="content">
    <h2>Bienvenido </h2>
    <p>Seleccione una opción del menú para comenzar.</p>
</div>
<?php include 'footer.php'; ?>
