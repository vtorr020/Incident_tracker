<?php
date_default_timezone_set('America/Bogota'); // 🕐 Zona horaria Colombia

$host = "mysql-incidenttracker.alwaysdata.net";
$usuario = "411820";
$contraseña = "Barcelona2025*";
$nombrebd = "incidenttracker_bd";

$conexion = new mysqli($host, $usuario, $contraseña, $nombrebd);

// Verificar conexión
if ($conexion->connect_error) {
    // echo "<p style='color:red; text-align:center;'>❌ Error de conexión: " . $conexion->connect_error . "</p>";
} else {
    // Establecer zona horaria en la sesión MySQL
    $conexion->query("SET time_zone = '-05:00'");
    // echo "<p style='color:green; text-align:center;'>✅ Conexión exitosa a la base de datos</p>";
}
?>
