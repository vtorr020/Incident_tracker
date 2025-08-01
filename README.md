# [📌Incident tracker]

**Incident Tracker** es una plataforma web diseñada para registrar, gestionar y hacer seguimiento a los incidentes detectados por el equipo SOC dentro de una organización. Permite mantener un control centralizado, aplicar medidas correctivas y garantizar trazabilidad en todas las áreas involucradas.

## 🌐 URL de acceso

🔗 [https://incidenttracker.alwaysdata.net](https://incidenttracker.alwaysdata.net)

## 👤 Roles del sistema

- **Super Administrador**: Gestión total (usuarios, empleados, tickets y módulos informativos)
- **Administrador**: Gestión de empleados y seguimiento de tickets
- **Otros roles** (Ej. Legal, BISO, IT, RRHH): Visualización, búsqueda y filtros según permisos

## 🔐 Inicio de sesión y seguridad

- Inicio de sesión con autenticación 2FA (correo electrónico)
- Bloqueo automático tras 3 intentos fallidos (requiere desbloqueo por parte de un Super Administrador)
- Recuperación de contraseña mediante correo

## 🧰 Funcionalidades principales

### 👥 Gestión de usuarios

- Crear usuarios con roles y credenciales seguras
- Visualizar usuarios activos, inactivos y bloqueados
- Editar información y estado de cualquier usuario

### 🧑‍💼 Gestión de empleados

- Registro de empleados con formulario completo
- Edición de información o estado de empleados
- Asociación de empleados a tickets

### 🎫 Gestión de tickets

- Crear ticket (asociando un empleado existente o registrando uno nuevo)
- Buscar por número, estado, nombre o fecha
- Editar información de tickets registrados

### 🛡️ Módulos informativos

- Edición de contenidos en secciones como:
  - Vulnerabilidades
  - “¿Sabías que?”
  - Concienciación
  - Seguridad informática

## 🧱 Tecnologías usadas

- **Frontend:** HTML, CSS, Bootstrap 5, jQuery, DataTables, SweetAlert2
- **Backend:** PHP (modularizado), MySQL
- **Complementos:** Select2, validaciones AJAX, sesiones seguras
- **Hosting:** AlwaysData

## 📦 Estructura del sistema

```
├── index.php                   # Página principal
├── login.php                  # Autenticación con 2FA
├── bd/conexion.php            # Conexión a base de datos
├── js/, css/                  # Archivos estáticos
├── modals/                    # Formularios en modales
├── views/                     # Vistas organizadas por módulos
└── README.md
```

## 📸 Capturas (sugerido agregar)

- Login con 2FA
- Panel de gestión de tickets
- Modales de creación/edición
- Tablas interactivas con filtros

## 👩‍💻 Autora

**Valentina Torres**  
📧 valentina.torres@ejemplo.com *(reemplazar por el real si se desea)*  
🛠 Desarrolladora del sistema Incident Tracker

---

Este sistema fue desarrollado con el objetivo de fortalecer el control interno de incidentes, facilitar la trazabilidad y promover la cultura de ciberseguridad organizacional.


