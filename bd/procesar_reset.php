<?php
include './conexion.php';



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    echo "<!DOCTYPE html><html><head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <meta charset='UTF-8'>
    </head><body>";

    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Todos los campos son obligatorios'
            }).then(() => { history.back(); });
        </script>";
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Las contraseñas no coinciden'
            }).then(() => { history.back(); });
        </script>";
        exit();
    }

    $stmt = $conexion->prepare("SELECT UUID_users, reset_expiration FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Token inválido',
                text: 'El enlace no es válido'
            }).then(() => { history.back(); });
        </script>";
        exit();
    }

    $user = $result->fetch_assoc();

    if (strtotime($user['reset_expiration']) < time()) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Enlace expirado',
                text: 'Solicita uno nuevo desde el formulario'
            }).then(() => { history.back(); });
        </script>";
        exit();
    }

    // Actualizar la contraseña (puedes encriptarla aquí si deseas)
    $stmtUpdate = $conexion->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiration = NULL WHERE UUID_users = ?");
    $stmtUpdate->bind_param("ss", $newPassword, $user['UUID_users']);

    if ($stmtUpdate->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Contraseña actualizada',
                text: 'Ahora puedes iniciar sesión',
                timer: 2500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar la contraseña'
            }).then(() => { history.back(); });
        </script>";
    }

    $stmtUpdate->close();
    $stmt->close();
    $conexion->close();
    echo "</body></html>";
}
