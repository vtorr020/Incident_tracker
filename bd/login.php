<?php
include './conexion.php';
require '../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "❌ Por favor, complete todos los campos.";
        exit();
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $sql = "
        SELECT 
            u.UUID_users, 
            u.username, 
            u.password, 
            u.email, 
            u.status, 
            r.name AS role
        FROM users u
        JOIN role r ON r.ID_role = u.ID_role
        WHERE u.email = ?
    ";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "❌ Error en la consulta SQL: " . $conexion->error;
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['UUID_users'];

        if ($user['status'] === 'Bloqueado') {
            echo "❌ Tu cuenta está bloqueada. Contacta al administrador.";
            exit();
        }

        if ($user['status'] === 'Inactivo') {
            echo "❌ Tu cuenta está desactivada. Contacta al administrador.";
            exit();
        }

        if ($password === $user['password']) {
            // Login exitoso, limpiar intentos fallidos
            $stmt_delete = $conexion->prepare("DELETE FROM attempts WHERE UUID_users = ?");
            $stmt_delete->bind_param("s", $user_id);
            $stmt_delete->execute();
            $stmt_delete->close();

            // Guardar sesión
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user_id;
            $_SESSION['UUID_users'] = $user_id;
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // ✅ Guardar rol

            // ========== Código 2FA ==========
            $code = random_int(100000, 999999);
            $expiration_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $stmt_check_code = $conexion->prepare("SELECT * FROM codes_2fa WHERE UUID_users = ?");
            $stmt_check_code->bind_param("s", $user_id);
            $stmt_check_code->execute();
            $res_code = $stmt_check_code->get_result();

            if ($res_code->num_rows > 0) {
                $stmt_update = $conexion->prepare("UPDATE codes_2fa SET code = ?, expiration_time = ? WHERE UUID_users = ?");
                $stmt_update->bind_param("sss", $code, $expiration_time, $user_id);
                $stmt_update->execute();
                $stmt_update->close();
            } else {
                $stmt_insert = $conexion->prepare("INSERT INTO codes_2fa (code, expiration_time, UUID_users) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("sss", $code, $expiration_time, $user_id);
                $stmt_insert->execute();
                $stmt_insert->close();
            }

            // ========== Enviar email ==========
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp-incidenttracker.alwaysdata.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'incidenttracker@alwaysdata.net';
                $mail->Password = 'Barcelona2025*';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                $mail->setFrom('incidenttracker@alwaysdata.net', 'Código incident tracker');
                $mail->addAddress($user['email']);
                $mail->isHTML(true);
                $mail->Subject = 'Código de Verificación';

                $template = file_get_contents('./plantilla_email.html');
                $template = str_replace('{{CODE}}', $code, $template);
                $mail->Body = $template;

                $mail->send();
                echo "success";
            } catch (Exception $e) {
                error_log("Error al enviar correo 2FA: " . $mail->ErrorInfo);
                echo "❌ No se pudo enviar el código 2FA al correo.";
            }
        } else {
            // Intento fallido
            $stmt_insert_attempt = $conexion->prepare("INSERT INTO attempts (UUID_attempts, UUID_users) VALUES (UUID(), ?)");
            $stmt_insert_attempt->bind_param("s", $user_id);
            $stmt_insert_attempt->execute();
            $stmt_insert_attempt->close();

            $stmt_count = $conexion->prepare("SELECT COUNT(*) AS attempts FROM attempts WHERE UUID_users = ?");
            $stmt_count->bind_param("s", $user_id);
            $stmt_count->execute();
            $result_count = $stmt_count->get_result()->fetch_assoc();
            $intentos = $result_count['attempts'];

            if ($intentos >= 3) {
                $stmt_block = $conexion->prepare("UPDATE users SET status = 'Bloqueado' WHERE UUID_users = ?");
                $stmt_block->bind_param("s", $user_id);
                $stmt_block->execute();
                $stmt_block->close();
                echo "❌ Tu cuenta ha sido bloqueada por múltiples intentos fallidos.";
            } else {
                $intentos_restantes = 3 - $intentos;
                echo "❌ Contraseña incorrecta. Te quedan $intentos_restantes intento(s).";
            }
        }
    } else {
        echo "❌ Usuario no encontrado o inactivo.";
    }

    $stmt->close();
    $conexion->close();
}
