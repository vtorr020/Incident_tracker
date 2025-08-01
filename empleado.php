<?php
include './bd/conexion.php'; // Incluir la conexión a la base de datos

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si existe el valor en la sesión
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitado';
$uuid_logged_user = $_SESSION['UUID_users'] ?? null;

// Si no existe en la sesión, lo buscamos desde la base de datos
if (!$uuid_logged_user && $username !== 'Invitado') {
    $stmt = $conexion->prepare("SELECT UUID_users FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($uuid_result);
    if ($stmt->fetch()) {
        $uuid_logged_user = $uuid_result;
        $_SESSION['UUID_users'] = $uuid_logged_user; // opcional: guardarlo en sesión
    }
    $stmt->close();
}

// Obtener usuarios administradores
$usuariosAdmin = [];
$sql_usuarios = "
    SELECT u.UUID_users, u.username, r.name AS rol
    FROM users u
    INNER JOIN role r ON u.ID_role = r.ID_role
    WHERE TRIM(r.name) IN ('Administrador', 'Super administrador')
";
$result_usuarios = $conexion->query($sql_usuarios);
if ($result_usuarios && $result_usuarios->num_rows > 0) {
    while ($user = $result_usuarios->fetch_assoc()) {
        $usuariosAdmin[] = $user;
    }
}

// Obtener roles
$rolesArray = [];
$result_roles = $conexion->query("SELECT * FROM role");
if ($result_roles && $result_roles->num_rows > 0) {
    while ($row = $result_roles->fetch_assoc()) {
        $rolesArray[] = $row;
    }
}



$conexion->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Incident Tracker</title>

    <!-- ✅ jQuery PRIMERO (obligatorio para Select2 y Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ Bootstrap CSS (usa solo una versión) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">

    <!-- ✅ Tu plantilla (SB Admin, FontAwesome, DataTables) -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- ✅ Iconos y fuentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <!-- ✅ SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ✅ Bootstrap JS (después de jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     <link rel="stylesheet" href="style.menu.css">
</head>



<style>


    #editEmployeeForm label,
    #editEmployeeForm .modal-title,
    #editEmployeeForm input {
        color: black;
    }

    #editEmployeeForm input::placeholder {
        color: black;
    }



    #addUserModal label,
    #addUserModal input {
        color: #000 !important;
        border-color: #ced4da;
        /* opcional: estilo de borde estándar Bootstrap */
    }
</style>


<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'menu.php'; ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <!-- Dropdown - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small"><?= $username; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>


                    </ul>

                </nav>







                <!-- Begin Page Content -->
                <div class="container-fluid">




                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Empleados</h1>
                    <div class="mb-4 text-left"> <!-- Cambié text-right a text-left -->
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus"></i> Crear Empleado
                        </button>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Empleados creados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive shadow-sm rounded">
                                    <table class="table table-hover table-bordered align-middle letras-negras" id="dataTable" width="100%" cellspacing="0">

                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th><i class="bi bi-person-fill"></i> Nombres</th>
                                                <th><i class="bi bi-person-lines-fill"></i> Apellidos</th>
                                                <th><i class="bi bi-key-fill"></i> ADP</th> <!-- Mostrando ADP -->
                                                <th><i class="bi bi-person-badge-fill"></i> Empleado</th> <!-- ✅ NUEVA COLUMNA -->
                                                <th><i class="bi bi-calendar-event-fill"></i> Fecha Creación</th>
                                                <th><i class="bi bi-briefcase-fill"></i> Cargo</th>
                                                <th><i class="bi bi-person-circle"></i> Creado por </th>
                                                <th class="text-center"><i class="bi bi-tools"></i> Acciones</th>
                                            </tr>
                                        </thead>


                                        <tbody id="userTableBody">
                                            <!-- Las filas se cargarán dinámicamente aquí -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Valentina Torres</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="logoutModalLabel">¿Estás seguro que quieres salir?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-dark">
                    Dale cerrar sesión si quieres salir o cancelar si quieres permanecer en la página.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="./sesion/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal para agregar nuevo empleado -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addEmployeeForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel" style="color: #000;">Registrar Empleado</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="mb-3">
                            <label for="ADP" class="form-label">ADP</label>
                            <input type="number" class="form-control" id="ADP" name="ADP" min="0" step="1" required>
                        </div>

                        <div class="mb-3 d-none">
                            <label for="status" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="status" name="status" value="Activo" readonly>
                        </div>


                        <div class="mb-3">
                            <label for="position" class="form-label">Cargo</label>
                            <input type="text" class="form-control" id="position" name="position" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario </label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <!-- Campo oculto con UUID del usuario logueado -->
                        <input type="hidden" id="UUID_users" name="UUID_users" value="<?= htmlspecialchars($uuid_logged_user) ?>">

                        <!-- Campo visible solo para mostrar el nombre del usuario logueado -->
                        <div class="mb-3">
                            <label class="form-label">Usuario actual</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Empleado</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JS para generar ID ADP único antes de enviar -->




    <!-- Modal para editar empleado -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editEmployeeForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Campo oculto con UUID del empleado -->
                        <input type="hidden" id="editUUIDEmployee" name="UUID_employees">

                        <!-- Campos editables -->
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="editFirstName" name="first_name" required
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="editLastName" name="last_name" required
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="mb-3">
                            <label for="editADP" class="form-label">ADP</label>
                            <input type="number" class="form-control" id="editADP" name="ADP" required > 
                        </div>

                        <!-- ✅ Campo solo de lectura -->
                        <div class="mb-3">
                            <label for="editEmpleadoUsername" class="form-label">Usuario Empleado</label>
                            <input type="text" class="form-control" id="editEmpleadoUsername" name="empleado_username" >
                        </div>




                        <div class="mb-3">
                            <label for="editPosition" class="form-label">Cargo</label>
                            <input type="text" class="form-control" id="editPosition" name="position" required
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                title="Solo se permiten letras y espacios.">
                        </div>

                        <!-- Campo oculto con UUID del usuario logueado -->
                        <input type="hidden" id="UUID_users" name="UUID_users" value="<?= htmlspecialchars($uuid_logged_user) ?>">

                        <!-- Campo visible solo para mostrar el nombre del usuario logueado -->
                        <div class="mb-3">
                            <label class="form-label">Usuario actual</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>


            </div>
        </div>
    </div>



    <!-- Script personalizado -->
    <script>
        $(document).ready(function() {

            function loadEmployeeTable() {
                $.ajax({
                    url: './backend/employees/get_employees.php',
                    type: 'GET',
                    success: function(data) {
                        if ($.fn.DataTable.isDataTable('#dataTable')) {
                            $('#dataTable').DataTable().clear().destroy();
                        }

                        $('#userTableBody').html(data);

                        $('#dataTable').DataTable({
                            pageLength: 10,
                            lengthMenu: [10, 25, 50, 100],
                            language: {
                                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                            },
                            ordering: true,
                            order: [
                                [0, 'asc']
                            ]
                        });
                    },
                    error: function() {
                        console.error('❌ Error al cargar los datos de empleados.');
                    }
                });
            }

            function addEmployee() {
                const formData = $('#addEmployeeForm').serialize();
                console.log("📤 Enviando:", formData);

                $.ajax({
                    url: './backend/employees/add_employee.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Empleado añadido',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#addEmployeeForm')[0].reset();

                            $('#addUserModal').on('hidden.bs.modal', function() {
                                $('#addTicketModal').modal('show'); // Abre el modal de tickets al cerrar el de empleado
                                $(this).off('hidden.bs.modal'); // Elimina el binding
                            });

                            $('#addUserModal').modal('hide'); // Oculta modal actual

                            loadEmployeeTable();
                        } else {
                            // Mostrar mensaje específico si es un error de duplicado
                            if (response.message.includes("ADP") || response.message.includes("usuario")) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Dato duplicado',
                                    text: response.message
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    html: `<strong>${response.message}</strong><br><pre>${JSON.stringify(response.debug_post, null, 2)}</pre>`
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error AJAX:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error crítico',
                            html: `<strong>No se pudo guardar.</strong><br><pre>${xhr.responseText}</pre>`
                        });
                    }
                });
            }


            function submitEditEmployeeForm() {
                const formData = $('#editEmployeeForm').serialize();
                $.ajax({
                    url: './backend/employees/edit_employee.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Empleado actualizado',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#editUserModal').modal('hide');
                            loadEmployeeTable();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar el empleado.'
                        });
                    }
                });
            }

            // 🔄 Carga inicial
            loadEmployeeTable();

            // 📥 Envío de nuevo empleado
            $('#addEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                addEmployee();
            });

            // ✏️ Botón editar
            $(document).on('click', '.btn-edit-user', function() {
                $('#editUUIDEmployee').val($(this).data('uuid_employees'));
                $('#editFirstName').val($(this).data('first_name'));
                $('#editLastName').val($(this).data('last_name'));
                $('#editADP').val($(this).data('adp'));
                $('#editEmpleadoUsername').val($(this).data('username'));
                $('#editPosition').val($(this).data('position'));
                $('#edit_UUID_users').val($(this).data('uuid_users'));

                $('#editUserModal').modal('show');
            });

            // 💾 Guardar cambios de edición
            $('#editEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                submitEditEmployeeForm();
            });

        });
    </script>

    <!--  jQuery (de tu plantilla) -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!--  Bootstrap Bundle (usa este para tooltips, dropdowns, modales, etc.) -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!--  SweetAlert2 (recomendado tenerlo aquí también) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--  Select2 JS (debe ir después de jQuery y Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!--  Plugin de animación (de tu plantilla) -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!--  Script principal de tu plantilla AdminLTE -->
    <script src="js/sb-admin-2.min.js"></script>

    <!--  DataTables -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!--  Script personalizado para DataTables (opcional, si lo usas) -->
    <script src="js/demo/datatables-demo.js"></script>


</body>

</html>