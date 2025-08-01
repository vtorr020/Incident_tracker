<?php
include './bd/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Incident Tracker - Login</title>
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .password-container {
            position: relative;
        }

        .password-container input {
            width: 100%;
            height: 45px;
            padding-right: 45px; /* Espacio para el icono */
            font-size: 16px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 28px; /* Tamaño más grande */
            color: #444;
        }

        .login-box form input[type="email"],
        .login-box form .password-container {
            margin-bottom: 15px;
        }

        .login-box form input[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Login Form -->
    <div class="login-box">
        <img src="./img/logo.png" class="avatar" alt="Avatar Image">
        <h1>Login</h1>
        <form id="loginForm">
            <!-- EMAIL INPUT -->
            <label for="email">Email</label>
            <input type="email" placeholder="Enter email" name="email" id="email" required>

            <!-- PASSWORD INPUT CON ICONO -->
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" placeholder="Enter password" name="password" id="password" required>
                <i id="togglePassword" class="bx bx-show-alt toggle-password"></i>
            </div>

            <!-- SUBMIT BUTTON -->
            <input type="submit" value="Log In">

            <!-- RESET PASSWORD LINK -->
            <div style="margin-top: -10px; text-align: center;">
                <a href="./reset_password.php" style="font-size: 0.9em;">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        // Mostrar/ocultar contraseña
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('bx-show-alt');
                this.classList.toggle('bx-hide');
            });
        });

        // AJAX para el login
        $('#loginForm').on('submit', function (e) {
            e.preventDefault(); // Evitar el submit normal
            $.ajax({
                url: './bd/login.php',
                type: 'POST',
                data: $('#loginForm').serialize(),
                success: function (response) {
                    if (response.trim() === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Correcto',
                            text: 'Su código ha sido reenviado por favor revise la bandeja de Recibidos o Spam',
                            showConfirmButton: false,
                            timer: 4000
                        }).then(() => {
                            window.location.href = './verificacion_2fa.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Login',
                            text: response,
                            showConfirmButton: true
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo procesar la solicitud.',
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
