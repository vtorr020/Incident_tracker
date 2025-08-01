<?php
include '../../bd/conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = ['status' => 'error', 'message' => 'Error desconocido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $uuid = $data['uuid'] ?? '';
    $ticket = $data['ticket'] ?? '';
    $priority = $data['priority'] ?? '';
    $type = $data['type'] ?? '';
    $status = $data['status'] ?? '';
    $description = $data['description'] ?? '';
    $intervention_area = $data['intervention_area'] ?? '';
    $description_area = $data['description_area'] ?? '';
    $date_incident = !empty($data['date_incident']) ? date('Y-m-d', strtotime($data['date_incident'])) : null;
    $date_resolved = !empty($data['date_resolved']) ? date('Y-m-d', strtotime($data['date_resolved'])) : null;

    // ✅ Convertir array de áreas en string si es necesario
    $intervention_area = is_array($intervention_area) ? implode(', ', $intervention_area) : $intervention_area;

    if (!empty($uuid)) {
        $stmt = $conexion->prepare("UPDATE employees_report SET 
            ticket = ?, priority = ?, type = ?, status = ?, description = ?, 
            intervention_area = ?, description_area = ?, date_incident = ?, date_resolved = ?
            WHERE UUID_report = ?");

        if ($stmt === false) {
            $response['message'] = 'Error al preparar la consulta: ' . $conexion->error;
        } else {
            $stmt->bind_param(
                'ssssssssss',
                $ticket, $priority, $type, $status, $description,
                $intervention_area, $description_area, $date_incident, $date_resolved, $uuid
            );

            if ($stmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Reporte actualizado correctamente.'];
            } else {
                $response['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        $response['message'] = 'UUID inválido.';
    }
}

echo json_encode($response);
