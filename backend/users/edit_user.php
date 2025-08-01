<?php
include '../../bd/conexion.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = trim($_POST['user_id'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $status   = trim($_POST['status'] ?? '');
    $roleId   = trim($_POST['role'] ?? '');

    // Validar campos obligatorios
    if (!$userId || !$username || !$email || !$status || !$roleId) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Todos los campos son obligatorios."
        ]);
        exit;
    }

    // Validar que el username no esté en uso por otro usuario
    $stmtUser = $conexion->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND UUID_users != ?");
    $stmtUser->bind_param("ss", $username, $userId);
    $stmtUser->execute();
    $stmtUser->bind_result($username_exists);
    $stmtUser->fetch();
    $stmtUser->close();

    if ($username_exists > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ El nombre de usuario ya está en uso por otro usuario."
        ]);
        exit;
    }

    // Validar que el email no esté en uso por otro usuario
    $stmtEmail = $conexion->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND UUID_users != ?");
    $stmtEmail->bind_param("ss", $email, $userId);
    $stmtEmail->execute();
    $stmtEmail->bind_result($email_exists);
    $stmtEmail->fetch();
    $stmtEmail->close();

    if ($email_exists > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ El correo electrónico ya está en uso por otro usuario."
        ]);
        exit;
    }

    // Actualizar los datos
    $sql = "UPDATE users SET username = ?, email = ?, status = ?, ID_role = ? WHERE UUID_users = ?";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Error al preparar la consulta: " . $conexion->error
        ]);
        exit;
    }

    $stmt->bind_param("sssss", $username, $email, $status, $roleId, $userId);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "✅ Usuario actualizado correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Error al actualizar el usuario: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conexion->close();
}
?>
