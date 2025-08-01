<?php
include './bd/conexion.php'; // Incluir la conexión a la base de datos

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si existe el valor en la sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
/**
 * ✅ Consulta única para contar usuarios
 */
$sql = "SELECT 
            COUNT(CASE WHEN status = 'Activo' THEN 1 END) AS total_activos,
            COUNT(CASE WHEN status = 'Inactivo' THEN 1 END) AS total_inactivos,
            COUNT(CASE WHEN status = 'Bloqueado' THEN 1 END) AS total_bloqueados
        FROM users";

$result = $conexion->query($sql);

// Si hay resultados, asignamos a las variables
if ($result && $row = $result->fetch_assoc()) {
    $totalUsuariosActivos = $row['total_activos'];
    $totalUsuariosInactivos = $row['total_inactivos'];
    $totalUsuariosBLoqueados = $row['total_bloqueados'];
}

/**
 * ✅ Consulta única para obtener los roles
 */
$roles = "SELECT * FROM role";
$result_roles = $conexion->query($roles);

// Crear un array para almacenar los roles
$rolesArray = [];

if ($result_roles->num_rows > 0) {
    while ($row = $result_roles->fetch_assoc()) {
        $rolesArray[] = $row;
    }
}



$usuariosActivos = [];
$sql_activos = "
 SELECT u.username, u.email, u.status,r.name
    FROM users u
    INNER JOIN role r ON
    u.ID_role=r.ID_role
    WHERE u.status = 'Activo'
";
$result_activos = $conexion->query($sql_activos);
if ($result_activos && $result_activos->num_rows > 0) {
    while ($row = $result_activos->fetch_assoc()) {
        $usuariosActivos[] = $row;
    }
}


$usuariosInactivos = [];
$sql_inactivos = "
     SELECT u.username, u.email, u.status,r.name
    FROM users u
    INNER JOIN role r ON
    u.ID_role=r.ID_role
    WHERE u.status = 'Inactivo'
";
$result_inactivos = $conexion->query($sql_inactivos);
if ($result_inactivos && $result_inactivos->num_rows > 0) {
    while ($row = $result_inactivos->fetch_assoc()) {
        $usuariosInactivos[] = $row;
    }
}


$usuariosBloqueados = [];
$sql_bloqueados = "
   SELECT u.username, u.email, u.status,r.name
    FROM users u
    INNER JOIN role r ON
    u.ID_role=r.ID_role
    WHERE u.status = 'Bloqueado'

";
$result_bloqueados = $conexion->query($sql_bloqueados);
if ($result_bloqueados && $result_bloqueados->num_rows > 0) {
    while ($row = $result_bloqueados->fetch_assoc()) {
        $usuariosBloqueados[] = $row;
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

    <title>Incident tracker</title>

    <!-- Iconos y fuentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.menu.css">
</head>




<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'menu.php'; ?>
        <!-- End of Sidebar -->

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

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $username; ?>
                                </span>

                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                <!-- End of Topbar -->
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4 ml-xl-3">
                        <div class="card border-left-success shadow h-100 py-2"
                            style="cursor: pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#modalUsuariosActivos">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            USUARIOS ACTIVOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $totalUsuariosActivos; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal fade" id="modalUsuariosActivos" tabindex="-1" aria-labelledby="modalUsuariosActivosLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header text-white" style="background-color: #198754;">
                                    <h5 class="modal-title" id="modalUsuariosActivosLabel">Usuarios activos</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>

                                <div class="modal-body">
                                    <?php if (!empty($usuariosActivos)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm text-center" id="tablaUsuariosActivos">
                                                <thead class=" text-center">
                                                    <tr>
                                                        <th class="text-center" style="background-color: #ADD7BD;">Usuario</th>
                                                        <th class="text-center" style="background-color: #ADD7BD;">Email</th>
                                                        <th class="text-center" style="background-color: #ADD7BD;">Rol</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach ($usuariosActivos as $usuario): ?>
                                                        <tr class="text-center">
                                                            <td><?= htmlspecialchars($usuario['username']) ?></td>
                                                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                                                            <td><?= htmlspecialchars($usuario['name']) ?></td> <!-- Mostrar el rol -->
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>




                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No hay usuarios activos.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer p-2">
                                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2"
                            style="cursor: pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#modalUsuariosInactivos">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            USUARIOS INACTIVOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $totalUsuariosInactivos; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="modal fade" id="modalUsuariosInactivos" tabindex="-1" aria-labelledby="modalUsuariosInactivosLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header text-white" style="background-color: #F04265;">
                                    <h5 class="modal-title" id="modalUsuariosInactivosLabel">Usuarios Inactivos</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>

                                <div class="modal-body p-3">
                                    <?php if (!empty($usuariosInactivos)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm text-center" id="tablaUsuariosInactivos">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th class="text-center"style="background-color: #FAC7D1;">Usuario</th>
                                                        <th class="text-center"style="background-color: #FAC7D1;">Email</th>
                                                        <th class="text-center" style="background-color: #FAC7D1;">Rol</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($usuariosInactivos as $usuario): ?>
                                                        <tr class="text-center">
                                                            <td><?= htmlspecialchars($usuario['username']) ?></td>
                                                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                                                            <td><?= htmlspecialchars($usuario['name']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>





                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No hay usuarios inactivos.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer p-2">
                                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2"
                            style="cursor: pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#modalUsuariosBloqueados">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            USUARIOS BLOQUEADOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $totalUsuariosBLoqueados; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-lock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>




                <div class="modal fade" id="modalUsuariosBloqueados" tabindex="-1" aria-labelledby="modalUsuariosBloqueadosLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="modalUsuariosBloqueadosLabel">Usuarios Bloqueados</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>

                            <div class="modal-body p-3">
                                <?php if (!empty($usuariosBloqueados)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm text-center" id="tablaUsuariosBloqueados">
                                            <thead class="text-center">
                                                <tr>
                                                    <th class="text-center"  style="background-color: #FBE09D;">Usuario</th>
                                                    <th class="text-center"  style="background-color: #FBE09D;">Email</th>
                                                    <th class="text-center"  style="background-color: #FBE09D;" >Rol</th> <!-- Cambiado de 'Estado' a 'Rol' -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($usuariosBloqueados as $usuario): ?>
                                                    <tr class="text-center">
                                                        <td><?= htmlspecialchars($usuario['username']) ?></td>
                                                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                                                        <td><?= htmlspecialchars($usuario['name']) ?></td> <!-- Mostrar el rol -->
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">No hay usuarios bloqueados.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer p-2">
                                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>









                <!-- Begin Page Content -->
                <div class="container-fluid">




                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Usuarios</h1>
                    <div class="mb-4 text-left"> <!-- Cambié text-right a text-left -->
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus"></i> Crear Usuario
                        </button>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Usuarios creados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive shadow-sm rounded">
                                <table class="table table-hover align-middle table-bordered border-light" id="dataTable">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th><i class="bi bi-person-fill"></i> Usuario</th>
                                            <th><i class="bi bi-envelope-fill"></i> Email</th>
                                            <th><i class="bi bi-check-circle-fill"></i> Estado</th>
                                            <th><i class="bi bi-calendar-event-fill"></i> Creado</th>
                                            <th><i class="bi bi-person-badge-fill"></i> Rol</th>
                                            <th class="text-center"><i class="bi bi-tools"></i> Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        <!-- Datos se cargarán dinámicamente -->
                                    </tbody>
                                </table>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="exampleModalLabel">Estas seguro que quieres salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-black">Dale cerrar sesión si quieres salir o cancelar si quieres permanecer en la página.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="./sesion/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Encabezado del Modal -->
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="addUserModalLabel">Añadir Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Cuerpo del Modal -->
                <div class="modal-body">
                    <!-- Formulario -->
                    <form id="addUserForm" method="POST">
                        <!-- Nombre de Usuario -->
                        <div class="mb-3">
                            <label for="username" class="form-label text-black">Nombre de Usuario</label>
                            <input type="text" class="form-control text-black" id="username" name="username" required>
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-black">Email</label>
                            <input type="email" class="form-control text-black" id="email" name="email" required>
                        </div>
                        <!-- Rol -->
                        <div class="mb-3">
                            <label for="role" class="form-label text-black">Rol</label>
                            <select class="form-select text-black" id="role" name="role" required>
                                <?php foreach ($rolesArray as $rol): ?>
                                    <option value="<?= $rol['ID_role'] ?>"><?= $rol['name'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <!-- Contraseña -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label text-black">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control text-black" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div id="passwordHelp" class="form-text text-danger"></div>
                        </div>




                        <!-- Botón para enviar -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                        </div>
                    </form>

                    <!-- Mensaje de éxito o error -->
                    <div id="responseMessage" style="display: none;" class="alert mt-3"></div>

                    <!-- Incluir jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        $(document).ready(function() {

                            function loadUserTable() {
                                $.ajax({
                                    url: './backend/users/get_users.php',
                                    type: 'GET',
                                    success: function(data) {
                                        if ($.fn.DataTable.isDataTable('#dataTable')) {
                                            $('#dataTable').DataTable().clear().destroy();


                                        }
                                        $('#userTableBody').html(data);

                                        aplicarEstiloEstado(); // 👉 Pinta los estados con colores
                                        $('#dataTable').DataTable({
                                            pageLength: 10,
                                            lengthMenu: [10, 25, 50, 100],
                                            language: {
                                                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                                            },
                                            order: [
                                                [0, 'asc']
                                            ]
                                        });
                                    },
                                    error: function() {
                                        console.error('Error al cargar los datos de la tabla.');
                                    }
                                });
                            }


                            function addUser() {
                                const formData = $('#addUserForm').serialize();
                                $.ajax({
                                    url: './backend/users/add_user.php',
                                    type: 'POST',
                                    data: formData,
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === "success") {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Usuario añadido',
                                                text: response.message,
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                            $('#addUserForm')[0].reset();
                                            $('#addUserModal').modal('hide');
                                            loadUserTable();
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
                                            text: 'Ocurrió un error al guardar el usuario.'
                                        });
                                    }
                                });
                            }


                            function submitEditUserForm() {
                                const formData = $('#editUserForm').serialize();
                                $.ajax({
                                    url: './backend/users/edit_user.php',
                                    type: 'POST',
                                    data: formData,
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === "success") {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Usuario actualizado',
                                                text: response.message,
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                            $('#editUserModal').modal('hide');
                                            loadUserTable();

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
                                            text: 'Ocurrió un error al actualizar el usuario.'
                                        });
                                    }
                                });
                            }


                            loadUserTable();


                            $('#addUserForm').on('submit', function(e) {
                                e.preventDefault();
                                addUser();
                            });


                            $(document).on('click', '.btn-edit-user', function() {
                                const user = {
                                    id: $(this).data('id'),
                                    username: $(this).data('username'),
                                    email: $(this).data('email'),
                                    status: $(this).data('status'),
                                    role: $(this).data('role')
                                };

                                $('#editUserId').val(user.id);
                                $('#editUsername').val(user.username);
                                $('#editEmail').val(user.email);
                                $('#editStatus').val(user.status);
                                $('#editRole').empty();

                                // Agregar opciones al select de roles desde PHP
                                <?php foreach ($rolesArray as $role): ?>
                                    $('#editRole').append(`
                    <option value="<?= $role['ID_role'] ?>" ${user.role === '<?= $role['name'] ?>' ? 'selected' : ''}>
                        <?= $role['name'] ?>
                    </option>
                `);
                                <?php endforeach; ?>

                                $('#editUserModal').modal('show');
                            });


                            $(document).on('click', '.btn-delete-user', function() {
                                const userId = $(this).data('id');
                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: "Esta acción no se puede deshacer.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: './backend/users/delete_user.php',
                                            type: 'POST',
                                            data: {
                                                user_id: userId
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.status === "success") {
                                                    Swal.fire('Eliminado!', response.message, 'success');
                                                    loadUserTable();
                                                } else {
                                                    Swal.fire('Error!', response.message, 'error');
                                                }
                                            },
                                            error: function() {
                                                Swal.fire('Error!', 'Ocurrió un error al intentar eliminar el usuario.', 'error');
                                            }
                                        });
                                    }
                                });
                            });

                            $('#editUserForm').on('submit', function(e) {
                                e.preventDefault();
                                submitEditUserForm();
                            });


                            $(document).ready(function() {
                                $('#modalUsuariosActivos').on('shown.bs.modal', function() {
                                    if (!$.fn.DataTable.isDataTable('#tablaUsuariosActivos')) {
                                        $('#tablaUsuariosActivos').DataTable({
                                            language: {
                                                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                                            },
                                            pageLength: 5,
                                            lengthMenu: [8, 10, 25, 50]
                                        });
                                    }
                                });
                            });


                            $(document).ready(function() {
                                $('#modalUsuariosInactivos').on('shown.bs.modal', function() {
                                    if (!$.fn.DataTable.isDataTable('#tablaUsuariosInactivos')) {
                                        $('#tablaUsuariosInactivos').DataTable({
                                            language: {
                                                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                                            },
                                            pageLength: 8
                                        });
                                    }
                                });
                            });


                            $(document).ready(function() {
                                $('#modalUsuariosBloqueados').on('shown.bs.modal', function() {
                                    if (!$.fn.DataTable.isDataTable('#tablaUsuariosBloqueados')) {
                                        $('#tablaUsuariosBloqueados').DataTable({
                                            language: {
                                                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                                            },
                                            pageLength: 8
                                        });
                                    }
                                });
                            });




                            function aplicarEstiloEstado() {
                                $('#userTableBody td:nth-child(3)').each(function() {
                                    const estadoTexto = $(this).text().trim().toLowerCase();
                                    let clase = 'bg-secondary';
                                    let icono = 'bi-question-circle-fill';

                                    if (estadoTexto === 'activo') {
                                        clase = 'bg-success';
                                        icono = 'bi-check-circle-fill';
                                    } else if (estadoTexto === 'inactivo') {
                                        clase = 'bg-danger'; // 🔴 ahora será rojo
                                        icono = 'bi-pause-circle-fill';
                                    } else if (estadoTexto === 'bloqueado') {
                                        clase = 'bg-warning'; // 👉 Amarillo
                                        icono = 'bi-lock-fill';
                                    }

                                    const estadoCapitalizado = estadoTexto.charAt(0).toUpperCase() + estadoTexto.slice(1);
                                    $(this).html(`
            <span class="badge ${clase} text-white badge-status">
                <i class="bi ${icono} me-1"></i>${estadoCapitalizado}
            </span>
        `);
                                });
                            }


                        });
                    </script>


                    <script>
                        // Mostrar/ocultar contraseña
                        document.getElementById('togglePassword').addEventListener('click', function() {
                            const field = document.getElementById('password');
                            const icon = document.getElementById('eyeIcon');
                            if (field.type === 'password') {
                                field.type = 'text';
                                icon.classList.replace('bi-eye', 'bi-eye-slash');
                            } else {
                                field.type = 'password';
                                icon.classList.replace('bi-eye-slash', 'bi-eye');
                            }
                        });

                        // Validación de contraseña
                        document.getElementById('password').addEventListener('input', function() {
                            const password = this.value;
                            const help = document.getElementById('passwordHelp');

                            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{15,}$/;

                            if (!regex.test(password)) {
                                help.textContent = "⚠️ La contraseña debe tener mínimo 15 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos.";
                                this.setCustomValidity("Inválido");
                            } else {
                                help.textContent = "";
                                this.setCustomValidity("");
                            }
                        });
                    </script>


                </div>
            </div>
        </div>
    </div>



    <!-- Modal para editar usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm">
                    <div class="modal-header">
                        <h5 class="modal-title text-black" id="editUserModalLabel">Editar Usuario</h5>
                        <button type="button" class="btn-close text-black" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="user_id"> <!-- Campo oculto para el ID del usuario -->
                        <div class="mb-3">
                            <label for="editUsername" class="form-label text-black">Nombre de Usuario</label>
                            <input type="text" class="form-control text-black" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label text-black">Correo Electrónico</label>
                            <input type="email" class="form-control text-black" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label text-black">Estado</label>
                            <select class="form-select text-black" id="editStatus" name="status" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label text-black">Rol</label>
                            <select class="form-select text-black" id="editRole" name="role" required>
                                <!-- Rellenar dinámicamente los roles desde la base de datos -->
                            </select>
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







    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>