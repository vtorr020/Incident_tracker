<?php
include '../../bd/conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $ADP        = trim($_POST['ADP'] ?? '');
    $position   = trim($_POST['position'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $UUID_users = trim($_POST['UUID_users'] ?? '');

    $debug = [
        "first_name" => $first_name,
        "last_name" => $last_name,
        "ADP" => $ADP,
        "position" => $position,
        "username" => $username,
        "UUID_users" => $UUID_users
    ];

    if ($first_name && $last_name && $ADP && $position && $username && $UUID_users) {
        
        // Validar que el usuario exista en la tabla users
        $checkUser = $conexion->prepare("SELECT COUNT(*) FROM users WHERE UUID_users = ?");
        $checkUser->bind_param("s", $UUID_users);
        $checkUser->execute();
        $checkUser->bind_result($exists);
        $checkUser->fetch();
        $checkUser->close();

        if ($exists == 0) {
            $response["message"] = "❌ El usuario seleccionado no existe.";
        } else {
            // Verificar si ADP o username ya existen en la tabla employees
            $checkDuplicate = $conexion->prepare("SELECT COUNT(*) FROM employees WHERE ADP = ? OR username = ?");
            $checkDuplicate->bind_param("ss", $ADP, $username);
            $checkDuplicate->execute();
            $checkDuplicate->bind_result($duplicateCount);
            $checkDuplicate->fetch();
            $checkDuplicate->close();

            if ($duplicateCount > 0) {
                $response["message"] = "❌ Ya existe un empleado con ese ADP o nombre de usuario.";
            } else {
                try {
                    $stmt = $conexion->prepare("
                        INSERT INTO employees (
                            first_name, last_name, ADP, position, username, UUID_users
                        ) VALUES (?, ?, ?, ?, ?, ?)
                    ");

                    if ($stmt) {
                        $stmt->bind_param("ssssss", $first_name, $last_name, $ADP, $position, $username, $UUID_users);
                        $stmt->execute();

                        $response["status"] = "success";
                        $response["message"] = "✅ Empleado registrado correctamente.";
                    } else {
                        $response["message"] = "❌ Error al preparar la consulta: " . $conexion->error;
                    }
                } catch (mysqli_sql_exception $e) {
                    $response["message"] = "❌ Error SQL: " . $e->getMessage();
                }
            }
        }

    } else {
        error_log("❌ Campos faltantes o vacíos:\n" . print_r($debug, true));
        $response["message"] = "❌ Todos los campos son obligatorios.";
        $response["debug"] = $debug;
    }
} else {
    $response["message"] = "❌ Método de solicitud no válido.";
}

$conexion->close();
echo json_encode($response, JSON_PRETTY_PRINT);
