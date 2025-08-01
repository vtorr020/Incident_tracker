<?php
include './bd/conexion.php'; // Incluir la conexión a la base de datos


// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado correctamente
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || !isset($_SESSION['UUID_users'])) {
    header('Location: index.php');
    exit;
}

// ✅ Asignar variables de sesión
$username = $_SESSION['username'];
$role = strtolower(trim($_SESSION['role']));
$uuid_logged_user = $_SESSION['UUID_users'];

// Establecer la zona horaria del sistema
date_default_timezone_set('America/Bogota');
$fechaHoy = date('Y-m-d');

/**
 * ✅ Contar usuarios por estado
 */
$sql_usuarios = "
    SELECT 
        COUNT(CASE WHEN status = 'Active' THEN 1 END) AS total_activos,
        COUNT(CASE WHEN status = 'Inactive' THEN 1 END) AS total_inactivos,
        COUNT(CASE WHEN status = 'Blocked' THEN 1 END) AS total_bloqueados
    FROM users
";
$result_usuarios = $conexion->query($sql_usuarios);
if ($result_usuarios && $row = $result_usuarios->fetch_assoc()) {
    $totalUsuariosActivos = $row['total_activos'];
    $totalUsuariosInactivos = $row['total_inactivos'];
    $totalUsuariosBLoqueados = $row['total_bloqueados'];
}

/**
 * ✅ Obtener roles
 */
$rolesArray = [];
$sql_roles = "SELECT * FROM role";
$result_roles = $conexion->query($sql_roles);
if ($result_roles && $result_roles->num_rows > 0) {
    while ($row = $result_roles->fetch_assoc()) {
        $rolesArray[] = $row;
    }
}

/**
 * ✅ Consulta principal de reportes (todos)
 */
$sql_reportes = "
    SELECT 
        r.UUID_report,
        r.ticket,
        r.priority,
        r.type,
        r.status,
        u.username AS Asignado,
        r.description,
        r.intervention_area,
        r.description_area,
        r.date_incident,
        r.date_resolved,
        r.UUID_employees,
        e.username
    FROM employees_report r
    INNER JOIN employees e ON r.UUID_employees = e.UUID_employees
    INNER JOIN users u ON u.UUID_users = e.UUID_users
    ORDER BY r.ticket DESC
";
$result = $conexion->query($sql_reportes);

/**
 * ✅ Empleados activos
 */
$empleados = [];
$sql_empleados = "
    SELECT UUID_employees, first_name, last_name, username 
    FROM employees 
    WHERE status = 'Activo' 
    ORDER BY created_at DESC
";

$result_empleados = $conexion->query($sql_empleados);
if ($result_empleados && $result_empleados->num_rows > 0) {
    while ($emp = $result_empleados->fetch_assoc()) {
        $empleados[] = $emp;
    }
}

/**
 * ✅ Reportes Abiertos
 */
$reportesAbiertos = [];
$sql_reportes_abiertos = "
    SELECT 
        er.UUID_report, 
        er.priority, 
        er.date_incident, 
        er.type, 
        er.ticket, 
        u.username AS Asignado,
        er.status, 
        er.description, 
        er.date_resolved, 
        er.UUID_employees, 
        er.intervention_area, 
        er.description_area,
        e.username AS usuario_empleado
    FROM employees_report er
    INNER JOIN employees e ON e.UUID_employees = er.UUID_employees
    INNER JOIN users u ON u.UUID_users = e.UUID_users
    WHERE er.status = 'Abierto'
    ORDER BY er.date_incident DESC
";
$result_abiertos = $conexion->query($sql_reportes_abiertos);
if ($result_abiertos && $result_abiertos->num_rows > 0) {
    while ($row = $result_abiertos->fetch_assoc()) {
        $reportesAbiertos[] = $row;
    }
}

/**
 * ✅ Reportes Pendientes 
 */
$reportesPendientes = [];
$sql_reportes_pendientes = "
    SELECT 
        er.UUID_report, 
        er.priority, 
        er.date_incident, 
        er.type, 
        er.ticket, 
        u.username AS Asignado,
        er.status, 
        er.description, 
        er.date_resolved, 
        er.UUID_employees, 
        er.intervention_area, 
        er.description_area,
        e.username AS usuario_empleado
    FROM employees_report er
    INNER JOIN employees e ON e.UUID_employees = er.UUID_employees
    INNER JOIN users u ON u.UUID_users = e.UUID_users
    WHERE er.status = 'Pendiente'
    ORDER BY er.date_incident DESC
";
$result_pendientes = $conexion->query($sql_reportes_pendientes);
if ($result_pendientes && $result_pendientes->num_rows > 0) {
    while ($row = $result_pendientes->fetch_assoc()) {
        $reportesPendientes[] = $row;
    }
}

/**
 * ✅ Reportes Cerrados
 */
$reportesCerrados = [];
$sql_reportes_cerrados = "
    SELECT 
        er.UUID_report, 
        er.priority, 
        er.date_incident, 
        er.type, 
        er.ticket, 
        u.username AS Asignado,
        er.status, 
        er.description, 
        er.date_resolved, 
        er.UUID_employees, 
        er.intervention_area, 
        er.description_area,
        e.username AS usuario_empleado
    FROM employees_report er
    INNER JOIN employees e ON e.UUID_employees = er.UUID_employees
    INNER JOIN users u ON u.UUID_users = e.UUID_users
    WHERE er.status = 'Cerrado'
    ORDER BY er.date_resolved DESC
";
$result_cerrados = $conexion->query($sql_reportes_cerrados);
if ($result_cerrados && $result_cerrados->num_rows > 0) {
    while ($row = $result_cerrados->fetch_assoc()) {
        $reportesCerrados[] = $row;
    }
}
$totalReportesCerrados = count($reportesCerrados);

// ✅ Cerrar conexión
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
    body {
        font-family: 'Nunito', sans-serif;
    }

    .nav-link i {
        transition: all 0.2s ease-in-out;
    }

    .nav-link:hover i {
        transform: scale(1.2);
        color: #0d6efd;
        /* Azul Bootstrap */
    }

    #ticketTable thead th {
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 0.85rem;
        padding: 10px 8px;
        white-space: nowrap;


    }

    #ticketTable td.acciones-col .btn {
        padding: 2px 6px;
        font-size: 0.75rem;
        margin: 1px;
    }



    td.details-control {
        cursor: pointer;
    }

    td.details-control::before {
        content: "+";
        font-weight: bold;
        margin-right: 5px;
    }

    tr.shown td.details-control::before {
        content: "-";
    }


    td.details-control {
        cursor: pointer;
    }

    .text-wrap {
        white-space: normal;
        word-break: break-word;
    }

    #addEmployeeForm input,
    #addEmployeeForm select,
    #addEmployeeForm textarea,

    #formCrearTicket label,
    #formCrearTicket input,
    #formCrearTicket select,
    #formCrearTicket textarea {
    color: black;
    }

    #formCrearTicket .modal-title {
    color: white !important;
    }

    /* Todo el texto de los formularios en negro */
    #formEditar,
    #formEditar label,
    #formEditar input,
    #formEditar select,
    #formEditar textarea,
    #formEditar .form-check-label,
    #formEditar .form-check {
    color: black;
    }

    /* Placeholders en gris (o blanco si prefieres) */
    #formEditar input::placeholder,
    #formEditar textarea::placeholder {
    color: #6c757d; /* gris Bootstrap */
    }

    /* Solo el título "Editar Reporte" en blanco */
    #formEditar .modal-title {
    color: white !important;
    }



</style>




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

                    <!-- Topbar Search -->

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
                        <div class="card border-left-danger shadow h-100 py-2" style="cursor:pointer"
                            data-bs-toggle="modal" data-bs-target="#modalReportesAbiertos">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            TICKETS ABIERTOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= count($reportesAbiertos); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- 🔴 Modal -->
                    <div class="modal fade" id="modalReportesAbiertos" tabindex="-1" aria-labelledby="modalReportesAbiertosLabel" aria-hidden="true">
                         <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="modalReportesAbiertosLabel">Tickets abiertos</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="tablaReportesAbiertos" class="table table-bordered table-striped">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th></th> <!-- ✅ Para el botón expandible "+" -->
                                                <th>Ticket</th>
                                                <th>Tipo</th>
                                                <th>Prioridad</th>
                                                <th>Asignado</th>
                                                <th>Empleado</th>
                                                <th>Área</th>
                                                <th>Fecha Incidente</th>
                                                <th>Fecha Resolución</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php foreach ($reportesAbiertos as $reporte): ?>
                                                <tr class="report-row"
                                                    data-description="<?= safe_html($reporte['description']) ?>"
                                                    data-area="<?= safe_html($reporte['description_area']) ?>">
                                                    <td class="details-control">
                                                        <i class="bi bi-plus-square-fill text-primary"></i>
                                                    </td>
                                                    <td><?= safe_html($reporte['ticket']) ?></td>
                                                    <td><?= safe_html($reporte['type']) ?></td>
                                                    <td>
                                                        <?php
                                                        $colorPrioridad = match (strtolower($reporte['priority'] ?? '')) {
                                                            'p1 - critica' => 'badge bg-danger',
                                                            'p2 - alta' => 'badge bg-warning text-dark',
                                                            'p3 - media' => 'badge bg-info text-dark',
                                                            'p4 - baja' => 'badge bg-success',
                                                            default => 'badge bg-secondary',
                                                        };
                                                        ?>
                                                        <span class="<?= $colorPrioridad ?>"><?= safe_html($reporte['priority']) ?></span>
                                                    </td>
                                                    <td><?= safe_html($reporte['Asignado']) ?></td>
                                                    <td><?= safe_html($reporte['usuario_empleado']) ?></td>
                                                    <td><?= safe_html($reporte['intervention_area']) ?></td>
                                                    <td>
                                                        <?php
                                                        $fecha = $reporte['date_incident'] ?? '';
                                                        echo $fecha ? date('d/m/Y ', strtotime($fecha)) : '';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $resuelta = $reporte['date_resolved'] ?? '';
                                                        echo $resuelta ? date('d/m/Y ', strtotime($resuelta)) : '-';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>



                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Modal: Reportes Pendientes -->
                    <div class="modal fade" id="modalReportesPendientes" tabindex="-1" aria-labelledby="modalReportesPendientesLabel" aria-hidden="true">
                          <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title" id="modalReportesPendientesLabel"> Tickets pendientes</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="tablaReportesPendientes" class="table table-bordered table-striped table-hover">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th></th> <!-- ✅ Para el botón expandible "+" -->
                                                <th>Ticket</th>
                                                <th>Tipo</th>
                                                <th>Prioridad</th>
                                                <th>Asignado</th>
                                                <th>Empleado</th>
                                                <th>Área</th>
                                                <th>Fecha Incidente</th>
                                                <th>Fecha Resolución</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php foreach ($reportesPendientes as $reporte): ?>
                                                <tr class="report-row"
                                                    data-description="<?= safe_html($reporte['description']) ?>"
                                                    data-area="<?= safe_html($reporte['description_area']) ?>">
                                                    <td class="details-control">
                                                        <i class="bi bi-plus-square-fill text-primary"></i>
                                                    </td>
                                                    <td><?= safe_html($reporte['ticket']) ?></td>
                                                    <td><?= safe_html($reporte['type']) ?></td>
                                                    <td>
                                                        <?php
                                                        $colorPrioridad = match (strtolower($reporte['priority'] ?? '')) {
                                                            'p1 - critica' => 'badge bg-danger',
                                                            'p2 - alta' => 'badge bg-warning text-dark',
                                                            'p3 - media' => 'badge bg-info text-dark',
                                                            'p4 - baja' => 'badge bg-success',
                                                            default => 'badge bg-secondary',
                                                        };
                                                        ?>
                                                        <span class="<?= $colorPrioridad ?>"><?= safe_html($reporte['priority']) ?></span>
                                                    </td>
                                                    <td><?= safe_html($reporte['Asignado']) ?></td>
                                                    <td><?= safe_html($reporte['usuario_empleado']) ?></td>
                                                    <td><?= safe_html($reporte['intervention_area']) ?></td>
                                                    <td>
                                                        <?php
                                                        $fecha = $reporte['date_incident'] ?? '';
                                                        echo $fecha ? date('d/m/Y ', strtotime($fecha)) : '';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $resuelta = $reporte['date_resolved'] ?? '';
                                                        echo $resuelta ? date('d/m/Y ', strtotime($resuelta)) : '-';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>




                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal: Reportes Cerrados -->
                    <div class="modal fade" id="modalReportesCerrados" tabindex="-1" aria-labelledby="modalReportesCerradosLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalReportesCerradosLabel">Tickets Cerrados</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="tablaReportesCerrados" class="table table-bordered table-striped table-hover">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th></th> <!-- ✅ Para el botón expandible "+" -->
                                                <th>Ticket</th>
                                                <th>Tipo</th>
                                                <th>Prioridad</th>
                                                <th>Asignado</th>
                                                <th>Empleado</th>
                                                <th>Área</th>
                                                <th>Fecha Incidente</th>
                                                <th>Fecha Resolución</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php foreach ($reportesCerrados as $reporte): ?>
                                                <tr class="report-row"
                                                    data-description="<?= safe_html($reporte['description']) ?>"
                                                    data-area="<?= safe_html($reporte['description_area']) ?>">
                                                    <td class="details-control">
                                                        <i class="bi bi-plus-square-fill text-primary"></i>
                                                    </td>
                                                    <td><?= safe_html($reporte['ticket']) ?></td>
                                                    <td><?= safe_html($reporte['type']) ?></td>
                                                    <td>
                                                        <?php
                                                        $colorPrioridad = match (strtolower($reporte['priority'] ?? '')) {
                                                            'p1 - critica' => 'badge bg-danger',
                                                            'p2 - alta' => 'badge bg-warning text-dark',
                                                            'p3 - media' => 'badge bg-info text-dark',
                                                            'p4 - baja' => 'badge bg-success',
                                                            default => 'badge bg-secondary',
                                                        };
                                                        ?>
                                                        <span class="<?= $colorPrioridad ?>"><?= safe_html($reporte['priority']) ?></span>
                                                    </td>
                                                    <td><?= safe_html($reporte['Asignado']) ?></td>
                                                    <td><?= safe_html($reporte['usuario_empleado']) ?></td>
                                                    <td><?= safe_html($reporte['intervention_area']) ?></td>
                                                    <td>
                                                        <?php
                                                        $fecha = $reporte['date_incident'] ?? '';
                                                        echo $fecha ? date('d/m/Y ', strtotime($fecha)) : '';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $resuelta = $reporte['date_resolved'] ?? '';
                                                        echo $resuelta ? date('d/m/Y ', strtotime($resuelta)) : '-';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>









                    <div class="col-xl-3 col-md-6 mb-4 ml-xl-3">
                        <div class="card border-left-warning shadow h-100 py-2" style="cursor:pointer"
                            data-bs-toggle="modal" data-bs-target="#modalReportesPendientes">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            TICKETS PENDIENTES
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= count($reportesPendientes); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2" style="cursor:pointer"
                            data-bs-toggle="modal" data-bs-target="#modalReportesCerrados">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            TICKETS CERRADOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $totalReportesCerrados; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>










                <!-- Begin Page Content -->
                <div class="container-fluid">




                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Ticket</h1>

                    <!-- Botón crear -->
                    <div class="mb-4">
                        <?php if ($role !== 'administrativo'): ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTicketModal">
                                <i class="bi bi-person-plus"></i> Crear Ticket
                            </button>
                        <?php endif; ?>


                    </div>

                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover border align-middle shadow-sm rounded" id="ticketTable" style="background: linear-gradient(to bottom right, #f8f9fa, #e9ecef); border-radius: 10px; overflow: hidden;">
                                <thead class="text-white text-center" style="background: linear-gradient(to right, #4e73df, #224abe); font-size: 0.95rem;">
                                    <tr>
                                        <th></th> <!-- Columna para el "+" -->
                                        <th>Ticket</th>
                                        <th>Prioridad</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Asignado</th>
                                        <th>Incidente</th>
                                        <th>Solución</th>
                                        <th>Usuario</th>
                                        <th>Área de Intervención</th>

                                        <th>Acciones</th> <!-- ✅ "Detalle Área" eliminado -->
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                    if (isset($result) && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $colorPrioridad = match (strtolower($row['priority'] ?? '')) {
                                                'p1 - critica' => 'badge bg-danger',
                                                'p2 - alta' => 'badge bg-warning text-dark',
                                                'p3 - media' => 'badge bg-info text-dark',
                                                'p4 - baja' => 'badge bg-success',
                                                default => 'badge bg-secondary',
                                            };

                                            $colorEstado = match (strtolower($row['status'] ?? '')) {
                                                'abierto' => 'badge bg-primary',
                                                'en progreso' => 'badge bg-warning text-dark',
                                                'resuelto', 'cerrado' => 'badge bg-success',
                                                default => 'badge bg-dark',
                                            };

                                            echo "<tr class='report-row'
                    data-description='" . htmlspecialchars($row['description']) . "'
                    data-area='" . htmlspecialchars($row['description_area']) . "'>";

                                            echo "<td class='details-control'><i class='bi bi-plus-square-fill text-primary'></i></td>";
                                            echo "<td>" . htmlspecialchars($row['ticket'] ?? '') . "</td>";
                                            echo "<td><span class='{$colorPrioridad}'>" . htmlspecialchars($row['priority'] ?? '') . "</span></td>";
                                            echo "<td>" . htmlspecialchars($row['type'] ?? '') . "</td>";
                                            echo "<td><span class='{$colorEstado}'>" . htmlspecialchars($row['status'] ?? '') . "</span></td>";
                                            echo "<td>" . htmlspecialchars($row['Asignado'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['date_incident'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['date_resolved'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['intervention_area'] ?? '') . "</td>";

                                            // ❌ Línea eliminada: Detalle Área
                                            // echo "<td>" . htmlspecialchars($row['description_area'] ?? '') . "</td>";
                                            echo "<td>
                    <div class='d-flex justify-content-center gap-1'>
                        <button class='btn btn-outline-primary btn-sm btn-editar'
                            title='Editar'
                            data-bs-toggle='modal'
                            data-bs-target='#modalEditar'
                            data-uuid='" . htmlspecialchars($row['UUID_report'] ?? '') . "'
                            data-ticket='" . htmlspecialchars($row['ticket'] ?? '') . "'
                            data-priority='" . htmlspecialchars($row['priority'] ?? '') . "'
                            data-type='" . htmlspecialchars($row['type'] ?? '') . "'
                            data-status='" . htmlspecialchars($row['status'] ?? '') . "'
                            data-asignee='" . htmlspecialchars($row['Asignado'] ?? '') . "'
                            data-description='" . htmlspecialchars($row['description'] ?? '') . "'
                            data-intervention_area='" . htmlspecialchars($row['intervention_area'] ?? '') . "'
                            data-description_area='" . htmlspecialchars($row['description_area'] ?? '') . "'
                            data-date_incident='" . htmlspecialchars($row['date_incident'] ?? '') . "'
                            data-date_resolved='" . htmlspecialchars($row['date_resolved'] ?? '') . "'
                            data-username='" . htmlspecialchars($row['username'] ?? '') . "'>
                            <i class='fas fa-edit'></i>
                        </button>
                    </div>
                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-muted'>No se encontraron tickets.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>





                        </div>

                    </div>



                </div>

                <!-- Modal Editar Reporte -->
                <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">

                    <div class="modal-dialog modal-lg">
                        <form class="modal-content" id="formEditar">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Reporte</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body row g-3">

                                <!-- UUID oculto para identificar el reporte -->
                                <input type="hidden" id="edit_uuid_report">

                                <!-- Ticket también oculto si no deseas que se edite -->


                                <div class="col-md-6">
                                    <label class="form-label">Ticket</label>
                                    <input type="text" id="edit_ticket" class="form-control" readonly>
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">Prioridad</label>
                                    <select id="edit_priority" class="form-select" required>
                                        <option value="">Selecciona prioridad</option>
                                        <option value="P1 - Critica">P1 - CRÍTICA</option>
                                        <option value="P2 - Alta">P2 - ALTA</option>
                                        <option value="P3 - Media">P3 - MEDIA</option>
                                        <option value="P4 - Baja">P4 - BAJA</option>
                                        <option value="P5 - Sin incidencia">P5 - SIN INCIDENCIA</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Estado</label>
                                    <select id="edit_status" class="form-select" required>
                                        <option value="">Selecciona estado</option>
                                        <option value="Abierto">Abierto</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Cerrado">Cerrado</option>
                                    </select>
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">Tipo</label>
                                    <input type="text" id="edit_type" class="form-control" required>
                                </div>



                                <div class="col-md-12">
                                    <label for="edit_description" class="form-label">Descripción del incidente </label>
                                    <textarea id="edit_description" name="description" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha Incidente</label>
                                    <input type="date" id="edit_date_incident" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha Solución</label>
                                    <input type="date" id="edit_date_resolved" name="date_resolved" class="form-control">
                                </div>


                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Área/s a intervenir:</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="edit_intervention_area[]" id="edit_biso" value="BISO">
                                        <label class="form-check-label" for="edit_biso">BISO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="edit_intervention_area[]" id="edit_rrhh" value="Recursos humanos">
                                        <label class="form-check-label" for="edit_rrhh">Recursos humanos</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="edit_intervention_area[]" id="edit_legal" value="Legal">
                                        <label class="form-check-label" for="edit_legal">Legal</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="edit_intervention_area[]" id="edit_it" value="IT">
                                        <label class="form-check-label" for="edit_it">IT</label>
                                    </div>
                                </div>


                                <div class="col-md-12 ">
                                    <label for="edit_description_area" class="form-label">Detalle de Área</label>
                                    <textarea id="edit_description_area" name="description_area" class="form-control" rows="4" required></textarea>
                                </div>






                                <div class="col-md-12">
                                    <label class="form-label">Usuario Empleado</label>
                                    <input type="text" id="edit_username" class="form-control" readonly>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>

                    </div>
                </div>









                <!-- Modal para agregar nuevo empleado -->
                <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addEmployeeForm" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserModalLabel">Registrar Empleado</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">


                                    <input type="hidden" id="UUID_users" name="UUID_users" value="<?= htmlspecialchars($uuid_logged_user ?? '') ?>">


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




                                    <div class="mb-3">
                                        <label for="position" class="form-label">Cargo</label>
                                        <input type="text" class="form-control" id="position" name="position" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                            title="Solo se permiten letras y espacios.">
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Usuario </label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>



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
                <!-- Modal para Crear Ticket -->
                <!-- Modal para Crear Ticket -->
                <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form id="formCrearTicket" class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addTicketModalLabel">
                                    <i class="bi bi-journal-plus"></i>
                                    <?php
                                    if ($role === 'super administrador') {
                                        echo 'Crear Ticket';
                                    } else {
                                        echo 'Visualizar Ticket'; // o lo que desees
                                    }
                                    ?>
                                </h5>

                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>



                            <div class="modal-body row g-3">


                                <!-- 👤 Empleado responsable -->
                                <div class="col-md-12">
                                    <label class="form-label">Empleado</label>
                                    <div class="d-flex align-items-center">
                                        <select name="UUID_employees" id="UUID_employees" class="form-select select2-empleado" style="width: 380px;" required>
                                            <option value="">Seleccione un empleado</option>
                                            <?php foreach ($empleados as $emp): ?>
                                                <option value="<?= htmlspecialchars($emp['UUID_employees']) ?>"
                                                    data-username="<?= htmlspecialchars($emp['username']) ?>">
                                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= htmlspecialchars($emp['username']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>


                                        <button type="button" class="btn ms-2" id="btnAgregarEmpleado" data-bs-toggle="modal" data-bs-target="#addUserModal" style="background-color: #198754; color: white;">
                                            <i class="bi bi-person-plus"></i> Crear Empleado
                                        </button>
                                    </div>
                                    <small class="text-muted">* Seleccione el empleado reportado</small>
                                </div>






                                <!--  Ticket -->
                                <div class="col-md-6">
                                    <label class="form-label">Ticket:</label>
                                    <input type="text" name="ticket" id="ticket" class="form-control" placeholder="Ej:  12345678" required>
                                </div>


                                <!--  Prioridad -->
                                <div class="col-md-6">
                                    <label class="form-label">Prioridad:</label>
                                    <select name="priority" id="priority" class="form-select" required>
                                        <option value="">Selecciona prioridad</option>
                                        <option value="P1 - Critica">P1 - CRÍTICA</option>
                                        <option value="P2 - Alta">P2 - ALTA</option>
                                        <option value="P3 - Media">P3 - MEDIA</option>
                                        <option value="P4 - Baja">P4 - BAJA</option>
                                        <option value="P5 - Sin incidencia">P5 - SIN INCIDENCIA</option>
                                    </select>
                                </div>

                                <!--  Tipo -->
                                <div class="col-md-6">
                                    <label class="form-label">Tipo:</label>
                                    <input type="text" name="type" id="type" class="form-control" placeholder="Ej: Passwords compromised, DNS File Transfer, etc">
                                </div>

                                <!-- Estado oculto -->
                                <div class="col-md-6 d-none">
                                    <label class="form-label">Estado:</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="Abierto" selected>Abierto</option>
                                    </select>
                                </div>


                                <!--  Fecha del incidente -->
                                <!-- Fecha del incidente -->
                                <div class="col-md-6">
                                    <label class="form-label">Fecha del incidente:</label>
                                    <div class="input-group">
                                        <input type="date" name="date_incident" id="date_incident" class="form-control">

                                    </div>
                                </div>


                                <!--  Campo oculto para "asignee" -->
                                <input type="hidden" name="asignee" id="asignee" value="">

                                <!--  Área de intervención -->
                                <div class="col-md-12">
                                    <label class="form-label">Área/s a intervenir:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intervention_area[]" value="BISO" id="area_biso">
                                        <label class="form-check-label" for="area_biso">BISO</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intervention_area[]" value="Recursos Humanos" id="area_rh">
                                        <label class="form-check-label" for="area_rh">Recursos Humanos</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intervention_area[]" value="Legal" id="area_legal">
                                        <label class="form-check-label" for="area_legal">Legal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intervention_area[]" value="IT" id="area_it">
                                        <label class="form-check-label" for="area_it">IT</label>
                                    </div>
                                </div>


                                <!--  Descripción -->
                                <div class="col-md-12">
                                    <label class="form-label">Descripción del incidente:</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe el reporte del empleado encontrado por SOC"></textarea  required>
                                </div>
                            </div>

                            <!-- ✅ Botones -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php
                // ✅ Función para evitar warnings si un valor es null
                function safe_html($value)
                {
                    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
                }
                ?>






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
                    <h5 class="modal-title" id="exampleModalLabel">Estas seguro que quieres salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Dale cerrar sesión si quieres salir o cancelar si quieres permanecer en la página.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="./sesion/logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>






<script>
$(document).ready(function () {
    // ✅ Inicializar Select2
    $('#UUID_employees').select2({
        placeholder: "Buscar empleado...",
        allowClear: true,
        width: 'resolve',
        dropdownParent: $('#addTicketModal')
    });

    // ✅ Fecha de hoy


    // ✅ Formato de expansión
    function formatExpand(row) {
        const descripcion = $(row).data('description') || 'Sin descripción';
        const area = $(row).data('area') || 'Sin detalle de área';
        return `
            <div class="p-2 text-wrap text-start">
                <strong>📌 Descripción:</strong><br>${descripcion}<br><br>
                <strong>🏷️ Detalle del Área:</strong><br>${area}
            </div>
        `;
    }

    // ✅ Función para activar DataTable con expansión
    function activarTablaConExpand(selectorTabla) {
        const tabla = $(selectorTabla).DataTable({
            pageLength: 10,
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
            order: [[1, 'desc']]
        });

        $(`${selectorTabla} tbody`).on('click', 'td.details-control', function () {
            const tr = $(this).closest('tr');
            const row = tabla.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).html('<i class="bi bi-plus-square-fill text-primary"></i>');
            } else {
                row.child(formatExpand(tr)).show();
                tr.addClass('shown');
                $(this).html('<i class="bi bi-dash-square-fill text-danger"></i>');
            }
        });
    }

    // ✅ Activar DataTables con expansión para todas las tablas
    activarTablaConExpand('#ticketTable');
    activarTablaConExpand('#tablaReportesAbiertos');
    activarTablaConExpand('#tablaReportesPendientes');
    activarTablaConExpand('#tablaReportesCerrados');

    // ✅ Actualizar select empleados dinámicamente
    function actualizarSelectEmpleados() {
        $.ajax({
            url: './backend/employees/get_empleados_json.php',
            method: 'GET',
            dataType: 'json',
            success: function (empleados) {
                const $select = $('#UUID_employees');
                $select.empty().append('<option value="">Seleccione un empleado</option>');
                empleados.forEach(emp => {
                    $select.append(`<option value="${emp.UUID_employees}" data-username="${emp.username}">${emp.first_name} ${emp.last_name} (${emp.username})</option>`);
                });
                $select.select2({
                    placeholder: "Buscar empleado...",
                    allowClear: true,
                    width: 'resolve',
                    dropdownParent: $('#addTicketModal')
                });
            },
            error: function () {
                console.error('❌ Error al actualizar el select de empleados');
            }
        });
    }

    // ✅ Registro de nuevo empleado
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $('#addEmployeeForm').serialize();
        $.post('./backend/employees/add_employee.php', formData, function (response) {
            if (response.status === "success") {
                Swal.fire({ icon: 'success', title: 'Empleado añadido', text: response.message, timer: 1500, showConfirmButton: false });
                $('#addEmployeeForm')[0].reset();
                $('#addUserModal').on('hidden.bs.modal', function () {
                    $('#addTicketModal').modal('show');
                    $(this).off('hidden.bs.modal');
                }).modal('hide');
                actualizarSelectEmpleados();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
            }
        }, 'json').fail(function () {
            Swal.fire({ icon: 'error', title: 'Error crítico', text: 'No se pudo guardar el empleado.' });
        });
    });

    // ✅ Lógica al abrir modal de editar
   $('#modalEditar').on('show.bs.modal', function (e) {
    const button = $(e.relatedTarget);
    const rawAreas = button.data('intervention_area') || '';
    const selectedAreas = rawAreas.split(',').map(area => area.trim());
    $('input[name="edit_intervention_area[]"]').prop('checked', false);
    selectedAreas.forEach(area => {
        $(`input[name="edit_intervention_area[]"][value="${area}"]`).prop('checked', true);
    });

    const estadoActual = button.data('status') || '';
    const rol = "<?php echo $_SESSION['role']; ?>";

    $('#edit_uuid_report').val(button.data('uuid') || '');
    $('#edit_ticket').val(button.data('ticket') || '');
    $('#edit_priority').val(button.data('priority') || '');
    $('#edit_type').val(button.data('type') || '');
    $('#edit_status').val(estadoActual);
    $('#edit_description').val(button.data('description') || '');
    $('#edit_description_area').val(button.data('description_area') || '');
    $('#edit_date_incident').val(button.data('date_incident') || '');
    $('#edit_date_resolved').val(button.data('date_resolved') || '');
    $('#edit_username').val(button.data('username') || '');

    // ✅ Aquí se aplica la lógica correcta
    const selectEstado = document.getElementById('edit_status');
    if (rol.toLowerCase() === 'administrativo' && estadoActual === 'Cerrado') {
        selectEstado.disabled = true;
    } else {
        selectEstado.disabled = false; // Asegura que otros roles sí puedan editar
    }
});


    // ✅ Guardar cambios al editar
    $('#formEditar').on('submit', function (e) {
        e.preventDefault();
        const selectedAreas = $('input[name="edit_intervention_area[]"]:checked').map(function () {
            return this.value;
        }).get();

        const formData = {
            uuid: $('#edit_uuid_report').val()?.trim() || '',
            ticket: $('#edit_ticket').val()?.trim() || '',
            priority: $('#edit_priority').val()?.trim() || '',
            type: $('#edit_type').val()?.trim() || '',
            status: $('#edit_status').val()?.trim() || '',
            description: $('#edit_description').val()?.trim() || '',
            intervention_area: selectedAreas,
            description_area: $('#edit_description_area').val()?.trim() || '',
            date_incident: $('#edit_date_incident').val() || '',
            date_resolved: $('#edit_date_resolved').val() || '',
            username: $('#edit_username').val()?.trim() || ''
        };

        fetch('./backend/employees/update_report.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('✅ Actualizado', data.message, 'success');
                $('#modalEditar').modal('hide');
                setTimeout(() => location.reload(), 800);
            } else {
                Swal.fire('⚠️ Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('❌ Error', error.message || 'Ocurrió un error inesperado.', 'error');
        });
    });

    // ✅ Crear nuevo ticket
    $('#formCrearTicket').on('submit', function (e) {
        e.preventDefault();
        const formData = {
            ticket: $('#ticket').val()?.trim() || '',
            priority: $('#priority').val()?.trim() || '',
            type: $('#type').val()?.trim() || '',
            status: $('#status').val()?.trim() || '',
            date_incident: $('#date_incident').val() || '',
            UUID_employees: $('#UUID_employees').val(),
            intervention_area: $('input[name="intervention_area[]"]:checked').map(function () {
                return this.value;
            }).get(),
            description: $('#description').val()?.trim() || '',
            description_area: $('#description_area').val()?.trim() || ''
        };

        if (!formData.ticket || !formData.priority || !formData.UUID_employees) {
            Swal.fire('⚠️ Faltan campos', 'Completa: Ticket, Prioridad, Empleado.', 'warning');
            return;
        }

        fetch('./backend/employees/crear_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('✅ Éxito', data.message, 'success');
                $('#addTicketModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                Swal.fire('⚠️ Error', data.message, 'error');
            }
        })
        .catch(() => {
            Swal.fire('❌ Error', 'No se pudo guardar el ticket.', 'error');
        });
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