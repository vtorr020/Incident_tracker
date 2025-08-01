<?php
include '../../bd/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['user_id'];

    // Verificar si se recibió el ID del usuario
    if (empty($userId)) {
        echo json_encode([
            "status" => "error",
            "message" => "ID de usuario no proporcionado."
        ]);
        exit();
    }

    // Consulta para eliminar el usuario
    $sql = "DELETE FROM users WHERE UUID_users = ?";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al preparar la consulta: " . $conexion->error
        ]);
        exit();
    }

    $stmt->bind_param("s", $userId);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Usuario eliminado correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al eliminar el usuario."
        ]);
    }

    $stmt->close();
    $conexion->close();
}
?>
