<?php
include '../../bd/conexion.php';
header('Content-Type: application/json');

// 📥 Obtener datos JSON desde el cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Datos no recibidos correctamente']);
    exit;
}

// ✅ Sanitizar datos obligatorios
$ticket = $conexion->real_escape_string($input['ticket'] ?? '');
$priority = $conexion->real_escape_string($input['priority'] ?? '');
$status = $conexion->real_escape_string($input['status'] ?? '');
$UUID_employees = $conexion->real_escape_string($input['UUID_employees'] ?? '');

// 🧼 Sanitizar datos opcionales
$type = $conexion->real_escape_string($input['type'] ?? '');

// ✅ Convertir array de checkboxes a string separado por comas
$intervention_area_array = $input['intervention_area'] ?? [];
if (is_array($intervention_area_array)) {
    $intervention_area = $conexion->real_escape_string(implode(', ', $intervention_area_array));
} else {
    $intervention_area = '';
}

$description = $conexion->real_escape_string($input['description'] ?? '');
$description_area = $conexion->real_escape_string($input['description_area'] ?? '');

// ✅ Validar y formatear fechas si existen
$date_incident = !empty($input['date_incident']) ? date('Y-m-d', strtotime($conexion->real_escape_string($input['date_incident']))) : null;
$date_resolved = !empty($input['date_resolved']) ? date('Y-m-d', strtotime($conexion->real_escape_string($input['date_resolved']))) : null;

// ⚠️ Verificar campos obligatorios
if (empty($ticket)) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el campo: ticket']);
    exit;
}
if (empty($priority)) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el campo: prioridad']);
    exit;
}
if (empty($status)) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el campo: estado']);
    exit;
}
if (empty($UUID_employees)) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el campo: empleado responsable']);
    exit;
}

// 🔁 Validar fechas
if (!empty($date_incident) && !empty($date_resolved) && $date_resolved < $date_incident) {
    echo json_encode(['status' => 'error', 'message' => 'La fecha de solución no puede ser anterior a la del incidente.']);
    exit;
}

// ❌ Verificar si el ticket ya existe
$check = $conexion->query("SELECT ticket FROM employees_report WHERE ticket = '$ticket' LIMIT 1");
if ($check && $check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Ya existe un ticket con ese número.']);
    exit;
}

// 📝 Insertar ticket
$sql = "
    INSERT INTO employees_report (
        ticket, priority, type, status, description,
        intervention_area, description_area, date_incident, date_resolved, UUID_employees
    ) VALUES (
        '$ticket', '$priority', '$type', '$status', '$description',
        '$intervention_area', '$description_area', " .
        ($date_incident ? "'$date_incident'" : "NULL") . ", " .
        ($date_resolved ? "'$date_resolved'" : "NULL") . ", 
        '$UUID_employees'
    )
";

// ✅ Ejecutar la consulta
if ($conexion->query($sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Ticket creado correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al insertar: ' . $conexion->error]);
}

$conexion->close();
