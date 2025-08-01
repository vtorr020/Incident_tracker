<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
    <!-- Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f1f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 1.3rem;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-envelope-lock-fill"></i> Recuperación de la contraseña
        </div>
        <div class="card-body">
            <form id="formRecuperar" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label"> Ingrese su correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ingresa tu correo" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-send"></i> Enviar enlace
                </button>
            </form>
        </div>
    </div>

<script>
    document.getElementById("formRecuperar").addEventListener("submit", function (e) {
        e.preventDefault();
        const email = document.getElementById("email").value;

        fetch("./bd/enviar_reset.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `email=${encodeURIComponent(email)}`
        })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status === "success" ? "success" : "error",
                    title: data.status === "success" ? "Enviado" : "Error",
                    text: data.status === "success"
                        ? "El enlace para recuperar su contraseña se ha enviado con éxito. Revise por favor la bandeja de Recibidos o Spam."
                        : data.message
                }).then(() => {
                    // ✅ Redirigir si fue exitoso
                    if (data.status === "success") {
                        window.location.href = "index.php";
                    }
                });
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo procesar la solicitud"
                });
            });
    });
</script>


</body>

</html>