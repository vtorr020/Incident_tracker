<?php
include './conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST['codigo']);
    session_start();
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM codes_2fa WHERE UUID_users = ? AND code = ? AND expiration_time > NOW()";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $user_id, $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ✅ Código correcto, enviamos respuesta JSON con el rol
        $role = isset($_SESSION['role']) ? trim($_SESSION['role']) : 'Invitado';

        echo json_encode([
            'status' => 'success',
            'role' => $role
        ]);
    } else {
        // ❌ Código incorrecto o expirado
        echo json_encode([
            'status' => 'error',
            'message' => 'El código es incorrecto o ha expirado. Inténtalo de nuevo.'
        ]);
    }

    $stmt->close();
    $conexion->close();
}
?>
