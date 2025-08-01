<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .input-group-text {
            cursor: pointer;
        }

        .form-text-warning {
            color: #dc3545;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="card p-4">
        <h4 class="text-center mb-4"><i class="bi bi-shield-lock-fill"></i> Restablecer Contraseña</h4>
        <form id="formReset" method="POST" action="./bd/procesar_reset.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <div class="input-group">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Ingresa nueva contraseña" required>
                    <span class="input-group-text" onclick="togglePassword('new_password', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <div id="passwordHelp" class="form-text-warning mt-1"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirma contraseña" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
        </form>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const icon = iconElement.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        document.getElementById("formReset").addEventListener("submit", function (e) {
            const pass1 = document.getElementById("new_password");
            const pass2 = document.getElementById("confirm_password");
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{15,}$/;

            if (!regex.test(pass1.value)) {
                e.preventDefault();
                pass1.reportValidity();
                return;
            }

            if (pass1.value !== pass2.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'Por favor, verifica que ambas contraseñas sean iguales.',
                    confirmButtonColor: '#007bff'
                });
            }
        });

        document.getElementById('new_password').addEventListener('input', function () {
            const password = this.value;
            const help = document.getElementById('passwordHelp');

            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{15,}$/;

            if (!regex.test(password)) {
                help.textContent = "⚠️ La contraseña debe tener mínimo 15 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos.";
                this.setCustomValidity("Contraseña insegura");
            } else {
                help.textContent = "";
                this.setCustomValidity("");
            }
        });
    </script>

</body>

</html>
