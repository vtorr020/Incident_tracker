<?php
include '../../bd/conexion.php';

$sql = "
SELECT 
    r.UUID_report,
    r.priority,
    r.date_incident,
    r.type,
    r.ticket,
    r.asignee,
    r.status,
    r.description,
    r.date_resolved,
    r.intervention_area,
    r.description_area,
    r.username,
    r.UUID_employees,
    e.first_name,
    e.last_name
FROM employees_report r
JOIN employees e ON r.UUID_employees = e.UUID_employees
";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nombreEmpleado = $row['first_name'] . ' ' . $row['last_name'];

       echo "<tr class='report-row'
    data-description='" . htmlspecialchars($row['description']) . "'
    data-area='" . htmlspecialchars($row['description_area']) . "'>
    
    <td class='details-control'><i class='bi bi-plus-square-fill text-primary'></i></td>
    
    <td>" . htmlspecialchars($row['ticket']) . "</td>
    <td>" . htmlspecialchars($row['priority']) . "</td>
    <td>" . htmlspecialchars($row['type']) . "</td>
    <td>" . htmlspecialchars($row['status']) . "</td>
    <td>" . htmlspecialchars($row['asignee']) . "</td>
    <td>" . htmlspecialchars($row['date_incident']) . "</td>
    <td>" . htmlspecialchars($row['date_resolved']) . "</td>
    <td>" . htmlspecialchars($row['username']) . "</td>
    <td>" . htmlspecialchars($row['description_area']) . "</td> 

    <td>
        <div class='d-flex justify-content-center gap-1'>
            <button class='btn btn-outline-primary btn-sm btn-editar'
                title='Editar'
                data-bs-toggle='modal' 
                data-bs-target='#modalEditar'
                data-uuid='" . htmlspecialchars($row['UUID_report']) . "'
                data-ticket='" . htmlspecialchars($row['ticket']) . "'
                data-priority='" . htmlspecialchars($row['priority']) . "'
                data-type='" . htmlspecialchars($row['type']) . "'
                data-status='" . htmlspecialchars($row['status']) . "'
                data-asignee='" . htmlspecialchars($row['asignee']) . "'
                data-description='" . htmlspecialchars($row['description']) . "'
                data-intervention_area='" . htmlspecialchars($row['intervention_area']) . "'
                data-description_area='" . htmlspecialchars($row['description_area']) . "'
                data-date_incident='" . htmlspecialchars($row['date_incident']) . "'
                data-date_resolved='" . htmlspecialchars($row['date_resolved']) . "'
                data-username='" . htmlspecialchars($row['username']) . "'>
                <i class='fas fa-edit'></i>
            </button>
            <button class='btn btn-danger btn-sm btn-delete-report' 
                data-id='" . htmlspecialchars($row['UUID_report']) . "'>
                <i class='fas fa-trash'></i>
            </button>
        </div>
    </td>
</tr>";


    }
} else {
    echo "<tr><td colspan='12' class='text-center text-muted'>No hay reportes registrados.</td></tr>";
}

$conexion->close();
?>
