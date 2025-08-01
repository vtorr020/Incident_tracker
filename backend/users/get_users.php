<?php
include '../../bd/conexion.php';

$sql = "SELECT tu.UUID_users, tu.username, tu.email, tu.status, tu.created_at, rol.name as roles 
        FROM users tu 
        INNER JOIN role rol 
        ON tu.ID_role = rol.ID_role";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='text-center'>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['created_at']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['roles']) . "</td>";

        echo "<td class='text-center align-middle'>
                <button 
                    class='btn btn-primary btn-sm btn-edit-user' 
                    data-id='" . htmlspecialchars($row['UUID_users']) . "' 
                    data-username='" . htmlspecialchars($row['username']) . "' 
                    data-email='" . htmlspecialchars($row['email']) . "' 
                    data-status='" . htmlspecialchars($row['status']) . "' 
                    data-role='" . htmlspecialchars($row['roles']) . "'>
                    <i class='fas fa-edit'></i> 
                </button>
             
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No hay datos disponibles</td></tr>";
}

$conexion->close();
