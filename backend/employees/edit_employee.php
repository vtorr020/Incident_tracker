<?php
include '../../bd/conexion.php';
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido."];

// Solo aceptar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uuid_employee = trim($_POST['UUID_employees'] ?? '');
    $first_name    = trim($_POST['first_name'] ?? '');
    $last_name     = trim($_POST['last_name'] ?? '');
    $adp           = trim($_POST['ADP'] ?? '');
    $position      = trim($_POST['position'] ?? '');
    $uuid_user     = trim($_POST['UUID_users'] ?? '');

    // 🟢 Usa el mismo nombre que tienes en el modal: name="empleado_username"
    $username      = trim($_POST['empleado_username'] ?? '');

    if ($uuid_employee && $first_name && $last_name && $adp && $position && $uuid_user && $username) {

        // Verificar si ADP ya está en uso por otro empleado
        $stmtADP = $conexion->prepare("SELECT COUNT(*) FROM employees WHERE ADP = ? AND UUID_employees != ?");
        $stmtADP->bind_param("ss", $adp, $uuid_employee);
        $stmtADP->execute();
        $stmtADP->bind_result($adp_exists);
        $stmtADP->fetch();
        $stmtADP->close();

        if ($adp_exists > 0) {
            $response["message"] = "❌ El número ADP ya está registrado por otro empleado.";
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }

        // Verificar si username ya está en uso por otro empleado
        $stmtUser = $conexion->prepare("SELECT COUNT(*) FROM employees WHERE username = ? AND UUID_employees != ?");
        $stmtUser->bind_param("ss", $username, $uuid_employee);
        $stmtUser->execute();
        $stmtUser->bind_result($username_exists);
        $stmtUser->fetch();
        $stmtUser->close();

        if ($username_exists > 0) {
            $response["message"] = "❌ El nombre de usuario ya está registrado por otro empleado.";
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }

        // Ejecutar actualización
        $stmt = $conexion->prepare("UPDATE employees 
            SET first_name = ?, last_name = ?, ADP = ?, position = ?, UUID_users = ?, username = ?
            WHERE UUID_employees = ?");

        if ($stmt) {
            $stmt->bind_param("sssssss", $first_name, $last_name, $adp, $position, $uuid_user, $username, $uuid_employee);

            if ($stmt->execute()) {
                $response["status"] = "success";
                $response["message"] = "✅ Empleado actualizado correctamente.";
            } else {
                $response["message"] = "❌ Error al ejecutar la actualización: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $response["message"] = "❌ Error al preparar la consulta: " . $conexion->error;
        }

    } else {
        $response["message"] = "❌ Todos los campos son obligatorios.";
        $response["debug_post"] = $_POST;
    }

} else {
    $response["message"] = "❌ Método de solicitud no válido.";
}

$conexion->close();
echo json_encode($response, JSON_PRETTY_PRINT);
