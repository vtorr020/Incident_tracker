<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación</title>
    <!-- Bootstrap y SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-size: 1.2em;
        }

        .form-control {
            text-align: center;
            font-size: 1.2em;
            letter-spacing: 3px;
        }
    </style>
</head>

<body>

    <div class="card p-4" style="width: 400px;">
        <div class="card-header">
            <i class="bi bi-shield-lock-fill"></i> Verificación de Seguridad
        </div>
        <div class="card-body">
            <form id="form2FA">
                <div class="mb-3">
                    <label for="codigo" class="form-label">Ingresa el código de 6 dígitos:</label>
                    <input type="text" id="codigo" name="codigo" maxlength="6" class="form-control" placeholder="******" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Verificar
                </button>
            </form>

            <div id="timer" class="mt-3 text-center text-danger"></div>

            <button id="reenviarCodigo" class="btn btn-secondary" disabled>
                <i class="bi bi-arrow-clockwise"></i> Reenviar Código
            </button>
        </div>
    </div>

    <script>
        const timerElement = document.getElementById('timer');
        const btnReenviar = document.getElementById('reenviarCodigo');
        let countdown;

        function iniciarTemporizador() {
            clearInterval(countdown);

            let expirationTime = parseInt(localStorage.getItem('expirationTime') || '0');

            if (!expirationTime || expirationTime < Date.now()) {
                expirationTime = Date.now() + 120000; // 2 minutos
                localStorage.setItem('expirationTime', expirationTime);
            }

            countdown = setInterval(() => {
                const tiempoRestante = expirationTime - Date.now();

                if (tiempoRestante <= 0) {
                    clearInterval(countdown);
                    timerElement.innerText = "El código ha expirado.";
                    btnReenviar.disabled = false;
                    localStorage.removeItem('expirationTime');
                } else {
                    const minutos = Math.floor(tiempoRestante / 60000);
                    const segundos = Math.floor((tiempoRestante % 60000) / 1000);
                    timerElement.innerText = `Código expira en: ${minutos}:${segundos < 10 ? '0' + segundos : segundos}`;
                }
            }, 1000);
        }

        iniciarTemporizador();

        $('#form2FA').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: './bd/validate_2fa.php',
                type: 'POST',
                data: $('#form2FA').serialize(),
                success: function(response) {
                    let res;

                    try {
                        res = JSON.parse(response);
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Respuesta del servidor inválida.',
                            showConfirmButton: true
                        });
                        return;
                    }

                    const rol = (res.role || '').trim().toLowerCase(); // Normalizar texto

                    console.log('Rol recibido:', rol); // Verificar en consola

                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Código Correcto',
                            text: 'Bienvenido al sistema',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            localStorage.removeItem('expirationTime');

                            // Redirige según el rol
                            if (rol === 'administrativo') {
                                window.location.href = './tickets.php';
                            } else if (rol === 'administrador') {
                                window.location.href = './empleado.php';
                            } else {
                                window.location.href = './dashboard.php';
                            }

                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Código Incorrecto',
                            text: res.message || 'Código inválido',
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo procesar la solicitud.',
                        showConfirmButton: true
                    });
                }
            });
        });



        $('#reenviarCodigo').on('click', function() {
            $.ajax({
                url: './bd/resend_2fa.php',
                type: 'POST',
                success: function(response) {
                    let res;

                    try {
                        res = JSON.parse(response);
                    } catch (e) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Enviado',
                            text: 'Su código ha sido reenviado. Revise Recibidos o Spam.',
                            showConfirmButton: true
                        });

                        iniciarTemporizador();
                        return;
                    }

                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Código Reenviado',
                            text: 'Revisa tu correo',
                            showConfirmButton: true
                        });

                        localStorage.setItem('expirationTime', res.expirationTime);
                        btnReenviar.disabled = true;
                        iniciarTemporizador();
                    } else if (res.status === 'active') {
                        localStorage.setItem('expirationTime', res.expirationTime);
                        iniciarTemporizador();

                        Swal.fire({
                            icon: 'info',
                            title: 'Código aún válido',
                            text: res.message,
                            showConfirmButton: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'No se pudo reenviar el código.',
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo contactar con el servidor.',
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>