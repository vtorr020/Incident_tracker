<?php

date_default_timezone_set('America/Colombia');

// Incluir la conexión a la base de datos
include  './conexion.php';
require '../vendor/autoload.php'; // PHPMailer


session_start();
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Verificar si el código aún está vigente
$sql_check = "SELECT expiration_time FROM codes_2fa WHERE UUID_users = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $user_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$row = $result->fetch_assoc();

// Validar si aún está vigente
// Siempre se genera un nuevo código sin importar si el anterior está vigente


// ✅ **Generar un nuevo código**
$code = random_int(100000, 999999);
$expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes'));

// ✅ **Actualizar el código y la fecha de expiración**
$sql_update = "UPDATE codes_2fa SET code = ?, expiration_time = ? WHERE UUID_users = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("sss", $code, $expiration_time, $user_id);
$stmt_update->execute();
$stmt_update->close();

// ✅ **Configuración del servidor SMTP AlwaysData**
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp-incidenttracker.alwaysdata.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'incidenttracker@alwaysdata.net';
    $mail->Password = 'Barcelona2025*';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
       $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('incidenttracker@alwaysdata.net', ' Reenvío código incident tracker');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Código de Verificación';

    // ✅ **Leer el template HTML y reemplazar el valor del código**
    $template = file_get_contents('../bd/plantilla_email.html'); // Ruta correcta al template
    $template = str_replace('{{CODE}}', $code, $template);

    $mail->Body = $template;

  if ($mail->send()) {
    echo json_encode([
        'status' => 'success',
        'expirationTime' => strtotime($expiration_time) * 1000 // JS usa milisegundos
    ]);
} else {
    error_log("Error al enviar el correo: " . $mail->ErrorInfo);
    echo json_encode(['status' => 'error', 'message' => 'No se pudo enviar el correo.']);
}

} catch (Exception $e) {
    error_log("Error en PHPMailer: " . $mail->ErrorInfo);
    echo "❌ No se pudo enviar el correo.";
}
?>
