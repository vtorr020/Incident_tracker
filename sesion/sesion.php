<?php
session_start(); // Iniciar sesión

include '../bd/conexion.php'; // Incluir la conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Por favor, complete todos los campos.";
        exit();
    }

    // Consulta para verificar al usuario en la base de datos
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Guardar el nombre de usuario en la sesión
            $_SESSION['username'] = $user['username'];

            // Verificamos que la sesión se haya guardado
            if (isset($_SESSION['username'])) {
                echo "✅ Sesión guardada correctamente: " . $_SESSION['username'];
            } else {
                echo "❌ Error al guardar la sesión.";
            }

            // Redirigir al dashboard
            header("Location: ../html/dashboard.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close(); // Cerrar la consulta preparada
}

$conexion->close(); // Cerrar la conexión
?>
