<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = strtolower(trim($_SESSION['role'] ?? 'invitado'));
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            <img src="./img/logo_incident.png" class="avatar" alt="Avatar Image">
        </div>
        <div class="sidebar-brand-text mx-3">Incident Tracker</div>
    </a>

    <!-- ================= SUPER ADMINISTRADOR ================= -->
    <?php if ($role === 'super administrador'): ?>
        <div class="sidebar-heading">ADMINISTRACIÓN</div>

        <!-- Usuarios (Solo Super Administrador) -->
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="bi bi-people-fill"></i>
                <span>Usuarios</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- ================= ADMINISTRADOR y SUPER ADMINISTRADOR ================= -->
    <?php if ($role === 'administrador' || $role === 'super administrador'): ?>
        <!-- Empleados -->
        <li class="nav-item">
            <a class="nav-link" href="empleado.php">
                <i class="bi bi-person-badge-fill"></i>
                <span>Empleados</span>
            </a>
        </li>

        <!-- Tickets -->
        <li class="nav-item">
            <a class="nav-link" href="tickets.php">
                <i class="fas fa-ticket-alt"></i>
                <span>Tickets</span>
            </a>
        </li>

        <div class="sidebar-heading">EXPANDE TU CONOCIMIENTO!</div>

        <!-- Vulnerabilidades -->
 <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="bi bi-bug-fill"></i>
        <span>Vulnerabilidades</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded px-3">

            <a class="collapse-item text-center" href="software_desactualizado.php">
                <i class="bi bi-arrow-clockwise text-primary d-block"></i>
                <span>Software desactualizado</span>
            </a>

            <a class="collapse-item text-center" href="protocolos_inseguros.php">
                <i class="bi bi-shield-exclamation text-warning d-block"></i>
                <span>Protocolos inseguros</span>
            </a>

            <a class="collapse-item text-center" href="USB.php">
                <i class="bi bi-usb-drive text-success d-block"></i>
                <span>USB y descargas</span>
            </a>

            <a class="collapse-item text-center" href="phishing.php">
                <i class="bi bi-envelope-exclamation text-danger d-block"></i>
                <span>Phishing</span>
            </a>
            
        </div>
    </div>
</li>


        <!-- ¿Sabías que...? -->
        <li class="nav-item d-flex justify-content-center">
            <a class="nav-link" href="sabias.php">
                <i class="bi bi-lightbulb-fill"></i>
                <span>¿Sabías que…?</span>
            </a>
        </li>

        <!-- Concienciación -->
        <li class="nav-item d-flex justify-content-center">
            <a class="nav-link" href="concienciacion.php">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Concienciación y Seguridad</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- ================= ADMINISTRATIVO ================= -->
    <?php if ($role === 'administrativo'): ?>
        <div class="sidebar-heading">MÓDULOS</div>
        <li class="nav-item">
            <a class="nav-link" href="tickets.php">
                <i class="fas fa-ticket-alt"></i>
                <span>Tickets</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- Toggle -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>