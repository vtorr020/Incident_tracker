<?php
// Incluir la conexión a la base de datos
include  './conexion.php';
require '../vendor/autoload.php'; // PHPMailer

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    error_log("Error: Las variables de sesión no están definidas.");
    die("❌ Error: Las variables de sesión no están definidas.");
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Generar un código aleatorio de 6 dígitos
$code = random_int(100000, 999999);

// Establecer el tiempo de expiración (5 minutos desde ahora)
$expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes'));


// Verificar si ya existe un código para ese usuario
$sql = "SELECT * FROM codes_2fa WHERE UUID_users = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql_update = "UPDATE codes_2fa SET code = ?, expiration_time = ? WHERE UUID_users = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("sss", $code, $expiration_time, $user_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    $sql_insert = "INSERT INTO codes_2fa (code, expiration_time, UUID_users) VALUES (?, ?, ?)";
    $stmt_insert->bind_param("sss", $code, $expiration_time, $user_id);
    $stmt_insert->execute();
    $stmt_insert->close();
}

// ✅ **Configuración del servidor SMTP AlwaysData**
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->SMTPDebug = 0; // Activar depuración para ver logs
    $mail->isSMTP();
    $mail->Host = 'smtp-incidenttracker.alwaysdata.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'incidenttracker@alwaysdata.net';
    $mail->Password = 'Barcelona2025*';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8'; // ✅ Solución clave
    $mail->Encoding = 'base64';

    $mail->setFrom('incidenttracker@alwaysdata.net', 'Incident tracker code');
    $mail->addAddress($email);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->isHTML(true);
    $mail->Subject = 'Código de Verificación';

    // ✅ Leer el template HTML y reemplazar el valor del código
    $template = file_get_contents(__DIR__ . './plantilla_email.html');
    $template = str_replace('{{CODE}}', $code, $template);

    $mail->Body = $template;

    if ($mail->send()) {
        // Enviar como JSON para que JS lo reciba
        echo json_encode([
            'status' => 'success',
            'expirationTime' => strtotime($expiration_time) * 1000 // en milisegundos para JS
        ]);
        exit();
    } else {
        error_log("Error al enviar el correo: " . $mail->ErrorInfo);
        echo "❌ No se pudo enviar el correo.";
    }
} catch (Exception $e) {
    error_log("Error en PHPMailer: " . $mail->ErrorInfo);
    echo "❌ No se pudo enviar el correo.";
}
