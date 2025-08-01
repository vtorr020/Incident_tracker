<?php
include '../../bd/conexion.php';

$sql = "SELECT UUID_employees, first_name, last_name, username 
        FROM employees 
        WHERE status = 'Activo'
        ORDER BY created_at DESC";

$result = $conexion->query($sql);
$empleados = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $empleados[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($empleados);
