<?php
include '../../bd/conexion.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    if ($username && $email && $password && $role) {
        // 🔍 Verificar si el username o el email ya existen
        $checkSql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";
        if ($checkStmt = $conexion->prepare($checkSql)) {
            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $data = $result->fetch_assoc();

            if ($data['count'] > 0) {
                echo json_encode(["status" => "error", "message" => "El nombre de usuario o correo electrónico ya están registrados."]);
                $checkStmt->close();
                $conexion->close();
                exit;
            }
            $checkStmt->close();
        }

        // 🔐 Hashear la contraseña (recomendado en producción)
        // $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, ID_role, created_at) VALUES (?, ?, ?, ?, NOW())";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("ssss", $username, $email, $password, $role);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Usuario añadido con éxito."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al guardar los datos: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conexion->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
    }
}

$conexion->close();
?>
