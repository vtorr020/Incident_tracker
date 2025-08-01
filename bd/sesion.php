<?php
include './conexion.php'; // Incluir el archivo de conexión a la base de datos
session_start(); // Iniciar la sesión
if (isset($_SESSION['username'])) {
    header("Location: ../html/logout.php"); // Redirigir si ya está logueado
    exit();
}
?>