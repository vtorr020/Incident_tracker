<?php
require '../vendor/autoload.php'; // PHPMailer
include './conexion.php';

// Configurar base_url para enlace de restablecimiento
//$base_url = "http://localhost/valentina"; // Cambiar en producción
$base_url = "https://incidenttracker.alwaysdata.net/"; // dominio hosting




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Correo no válido']);
        exit();
    }

    // Buscar usuario por correo
    // Buscar usuario por correo y estado Activo
    $sql = "SELECT UUID_users FROM users WHERE email = ? AND status = 'Activo'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Correo no registrado o inactivo']);
        exit();
    }

    $user = $result->fetch_assoc();
    $uuid = $user['UUID_users'];
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar token y expiración en tabla users
    $update = $conexion->prepare("UPDATE users SET reset_token = ?, reset_expiration = ? WHERE UUID_users = ?");
    $update->bind_param("sss", $token, $expira, $uuid);
    $update->execute();

    // Enviar correo
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-incidenttracker.alwaysdata.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'incidenttracker@alwaysdata.net';
        $mail->Password = 'Barcelona2025*';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8'; // ✅ Solución clave
        $mail->Encoding = 'base64';

        $mail->setFrom('incidenttracker@alwaysdata.net', 'Sistema de Seguridad');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Restablecimiento de contraseña';

        $link = "$base_url/update_password.php?token=$token"; // Corrige el nombre si estaba mal

        $mail->Body = '
<div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="
        max-width: 600px;
        margin: auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-top: 6px solid #007bff;
    ">
        <h2 style="color: #333333; text-align: center;">🔒 Solicitud de restablecimiento de contraseña</h2>
        <p style="color: #555555; font-size: 16px;">
            Hemos recibido una solicitud para restablecer tu contraseña. Si no fuiste tú, puedes ignorar este mensaje.
        </p>
        <p style="color: #555555; font-size: 16px;">
            Para continuar, haz clic en el siguiente botón:
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="' . $link . '" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-size: 16px;">
                Restablecer contraseña
            </a>
        </div>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="text-align: center; color: #aaaaaa; font-size: 0.9em;">
            Este enlace expirará en 1 hora.<br>
            &copy; ' . date('Y') . ' Sistema de Seguridad
        </p>
    </div>
</div>';






        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Correo enviado con el enlace.']);
    } catch (Exception $e) {
        error_log("Mailer error: " . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
    }
}
