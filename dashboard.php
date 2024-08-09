<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';
?>
<div class="content">
    <h2>Bienvenido al Sistema de Facturación del Taller Mecánico</h2>
    <p>Este sistema le permite gestionar las facturas, clientes y servicios de su taller mecánico de manera eficiente. Seleccione una opción del menú para comenzar.</p>
</div>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Facturación del Taller Mecánico</title>
    <link rel="stylesheet" href="inicio.css"> 
</head>
<body>
    <header>
        <h1>Sistema de Facturación de Taller Mecánico El Compita</h1>
    </header>
