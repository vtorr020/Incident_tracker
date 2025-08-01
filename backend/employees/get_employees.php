<?php
include '../../bd/conexion.php';
header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = "
SELECT 
    e.UUID_employees,
    e.UUID_users, 
    e.first_name,
    e.last_name,
    e.ADP,
    e.created_at,
    e.position,
    e.username AS empleado_username,
    u.username AS usuario_username,
    r.name AS rol
FROM employees e
INNER JOIN users u ON e.UUID_users = u.UUID_users
INNER JOIN role r ON u.ID_role = r.ID_role
ORDER BY e.created_at DESC;
";

$result = $conexion->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='text-center'>";
        echo "<td>" . htmlspecialchars($row['first_name'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['last_name'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['ADP'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['empleado_username'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['position'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['usuario_username'] ?? '') . " (" . htmlspecialchars($row['rol'] ?? '') . ")</td>";
        echo "<td>
                <button class='btn btn-sm btn-primary btn-edit-user'
                    data-uuid_employees='" . htmlspecialchars($row['UUID_employees'] ?? '') . "'
                    data-first_name='" . htmlspecialchars($row['first_name'] ?? '') . "'
                    data-last_name='" . htmlspecialchars($row['last_name'] ?? '') . "'
                    data-adp='" . htmlspecialchars($row['ADP'] ?? '') . "'
                    data-username='" . htmlspecialchars($row['empleado_username'] ?? '') . "'
                    data-position='" . htmlspecialchars($row['position'] ?? '') . "'
                    data-uuid_users='" . htmlspecialchars($row['UUID_users'] ?? '') . "'
                >
                    <i class='fas fa-edit'></i>
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center text-muted'>No se encontraron empleados registrados.</td></tr>";
}

$conexion->close();
?>
