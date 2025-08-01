-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql-incidenttracker.alwaysdata.net
-- Generation Time: Aug 01, 2025 at 06:58 PM
-- Server version: 10.11.13-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `incidenttracker_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `UUID_attempts` varchar(36) NOT NULL,
  `attempt_time` timestamp NOT NULL,
  `UUID_users` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `codes_2fa`
--

CREATE TABLE `codes_2fa` (
  `UUID_2FA` varchar(36) NOT NULL DEFAULT uuid(),
  `code` varchar(6) NOT NULL,
  `expiration_time` timestamp NOT NULL,
  `UUID_users` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `codes_2fa`
--

INSERT INTO `codes_2fa` (`UUID_2FA`, `code`, `expiration_time`, `UUID_users`) VALUES
('01d0a6aa-6e1a-11f0-82d3-5254007e02a0', '689637', '2025-08-01 14:12:06', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('76408bc2-6e38-11f0-82d3-5254007e02a0', '610288', '2025-08-01 15:08:29', '4584e3de-5d58-11f0-82d3-5254007e02a0'),
('e6bc9fe6-6e13-11f0-82d3-5254007e02a0', '583365', '2025-08-01 13:16:09', '27779074-6e13-11f0-82d3-5254007e02a0');

-- --------------------------------------------------------

--
-- Table structure for table `editor_docs`
--

CREATE TABLE `editor_docs` (
  `UUID_doc` char(36) NOT NULL DEFAULT uuid(),
  `title` varchar(100) NOT NULL,
  `html_content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UUID_users` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `editor_docs`
--

INSERT INTO `editor_docs` (`UUID_doc`, `title`, `html_content`, `created_at`, `updated_at`, `UUID_users`) VALUES
('38fe7751-5af9-11f0-82d3-5254007e02a0', 'Software Desactualizado', '<p class=\"ql-align-justify\">🔎 <strong>¿Qué es un software desactualizado?</strong></p><p class=\"ql-align-justify\">Tener software sin actualizar equivale a obtener puertas abiertas a ciberataques. Aunque funcione correctamente el sistema, ese programa puede contener vulnerabilidades conocidas que permiten a otras personas acceder, alterar o robar información sensible de la compañía.</p><p class=\"ql-align-justify\">Las vulnerabilidades encontradas mediante escaneos se clasifican mediante códigos conocidos como CVE (Common Vulnerabilities and Exposures), el cual es un sistema global que identifica debilidades específicas en aplicaciones, sistemas operativos o plataforma.</p><p class=\"ql-align-justify\">🧠 <strong>Por ejemplo:</strong> Un CVE como el cual describe un fallo grave en Google Chrome, permite ejecutar código malicioso si el navegador no se actualiza. Este tipo de fallo puede ser explotado cuando se abre una página web que esta comprometida.</p><p class=\"ql-align-justify\">🚨 <strong>¿Por qué es peligroso?</strong></p><ul><li class=\"ql-align-justify\">&nbsp;Se expone a ciberataques masivos.</li><li class=\"ql-align-justify\">Si no aplicamos parches de seguridad, un computador queda vulnerable.</li><li class=\"ql-align-justify\">Al tener una auditoria escanean redes buscando versiones obsoletas y si se encuentra alguna puede que no pase la campaña la auditoria hasta que sea remediada.</li><li class=\"ql-align-justify\">Riesgo de ransomware (el ransomware es un tipo de software malicioso que bloquea los archivos del sistema y exige un pago para liberarlos) Algunos ejemplos es WannaCry la cuales afectaron a empresas de todo el mundo, incluyendo en Colombia, por no haber actualizado el sistema operativo Windows a tiempo.</li><li class=\"ql-align-justify\">Software EOL (End of Life) (Cuando un producto llega a su fin de vida útil (EOL), el fabricante ya no ofrece parches ni soporte. Usar estos sistemas equivale a dejar una vulnerabilidad permanente como por ejemplo: Internet Explorer (Recuerda que estamos en proceso para que este navegador no sea utilizado, solo se utilicen los recursos de el).</li></ul><p class=\"ql-align-justify\">🏢 <strong>Recuerda, en Foundever:</strong></p><p class=\"ql-align-justify\">✅ Solo está permitido usar software con soporte activo y parches actualizados.</p><p class=\"ql-align-justify\">✅ ¡Las actualizaciones pueden pedirse al equipo SCCM, revisas Software Center y ahí encuentras las actualizaciones!</p><p class=\"ql-align-justify\">❌ Está prohibido instalar, mantener o utilizar software en estado EOL.</p><p class=\"ql-align-justify\">⚠️<strong>Recomendaciones:</strong></p><ul><li class=\"ql-align-justify\">Verifica que las herramientas estén actualizadas: Office, navegadores, Zoom, etc. Puedes revisar mediante \"appwiz.cpl\" y ahí te muestra la ultima vez que fue actualizado y la versión que tienes.</li><li class=\"ql-align-justify\">Revisa siempre que el computador se encuentre con Windows 11 ya que nos encontramos en migración.</li><li class=\"ql-align-justify\">No instales versiones “portables” o no autorizadas de software.</li><li class=\"ql-align-justify\">Importante que se conecten siempre a la VPN para que todas las políticas empresariales sean aplicadas.</li></ul>', '2025-07-07 06:11:29', '2025-07-31 15:37:51', NULL),
('awareness-policy-001', 'Política de Concientización y Seguridad', '<h1>Política de concientización y seguridad informática</h1><p><br></p><p>&nbsp;&nbsp;&nbsp;- Riesgo de ransomware (el ransomware es un tipo de software malicioso que bloquea los archivos del sistema y exige un pago para liberarlos) Algunos ejemplos es WannaCry la cuales afectaron a empresas de todo el mundo, incluyendo en Colombia, por no haber actualizado el sistema operativo Windows a tiempo.</p><h2>1. Objetivo</h2><p>Garantizar que todo el personal esté adecuadamente informado sobre las amenazas informáticas y sepa cómo prevenir incidentes.</p><h2>2. Buenas Prácticas</h2><ul><li>No compartir contraseñas con terceros.</li><li>No abrir enlaces sospechosos ni correos desconocidos.</li><li>Usar doble autenticación cuando sea posible.</li><li>Actualizar regularmente los sistemas y antivirus.</li></ul><h2>3. Reportes</h2><p>En caso de incidentes o dudas, contactar de inmediato al área de TI.</p>', '2025-07-20 23:36:07', '2025-07-31 16:10:56', NULL),
('phishing-001', 'Seguridad Informática: Phishing', '<p>🎣 <strong>¿Qué es el phishing?</strong></p><p>Es una técnica de engaño mediante la cual un atacante se hace pasar como una entidad confiables (seaun banco, una empresa, un jefe o una plataforma conocida) para robar información sensible de usuarios, contraseñas, datos bancarios, acceso a sistemas, etc.</p><p>El medio más común es el correo electrónico, en la parte superior de Outlook puedes reportar este como phishing.</p><p>&nbsp;&nbsp;🧠 <strong>Dato clave:</strong></p><p>&nbsp;&nbsp;&nbsp;El phishing no requiere conocimientos técnicos del atacante. Solo necesita que una persona haga clic donde no debe.</p><p>🚩 <strong>¿Cómo reconocer un correo de phishing?</strong></p><ul><li>&nbsp;Correos que te generan presión para que abras un archivo o ingreses a un enlace.</li><li>En ocasiones se presentas errores de redacción.</li><li>Revisa el nombre y dominio desde donde es enviado, por lo general te muestran un mensaje donde dice que este destinatario es externo.</li><li>Archivos adjuntos peligrosos de PDF, Word o Excel con macros, o .zip con contenido ejecutable (.exe, .bat, .scr).</li><li>Enlaces que parecen legítimos pero llevan a sitios falsos por ejemplo el enlace puede decir “www.banco.com”, pero al hacer clic te redirige a un dominio falso como bancolour.login-alerts.xyz.</li></ul><p>🏢<strong> Recuerda:</strong></p><p>✅ No hagas clic en enlaces ni abras archivos de correos sospechosos, incluso si parecen venir de un compañero.</p><p>✅ Todo correo que parezca irregular debe ser reportado de inmediato por la opción de reportar phishing que se escuentra en la parte superior de tu correo.</p><p>🛡️<strong> ¿Qué puede pasar si caes?</strong></p><ul><li>Robo de credenciales.</li><li>Accesos no autorizados.</li><li>Suplantación interna (el atacante usa tu cuenta para engañar a otros).</li></ul>', '2025-07-07 15:34:19', '2025-07-31 17:10:03', NULL),
('PROTOCOLOS_001', 'Protocolos inseguros y recomendaciones', '<p class=\"ql-align-justify\">🔒<strong>¿Qué es un protocolo?</strong></p><p class=\"ql-align-justify\">Es el conjunto de reglas que permiten que dos dispositivos se puedan comunicar entre si. Algunos protocolos simplemente transportan información, otros además la protegen usando técnicas de cifrado.</p><p class=\"ql-align-justify\">En un entorno empresarial, elegir un protocolo inseguro puede ser enviar una carta confidencial sin sobre, para que cualquier persona pueda leerla.</p><p class=\"ql-align-justify\">En la actualidad, hay muchos protocolos antiguos que no deben usarse, debido a que no cuentan con cifrado, autenticación moderna, ni protecciones contra ataques. Por ejemplo:</p><p class=\"ql-align-justify\">🖥️ <strong>Telnet</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;<strong>¿Qué es?</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;Es un protocolo para&nbsp;acceder remotamente a dispositivos y servidores.</p><p class=\"ql-align-justify\">&nbsp;<strong>&nbsp;¿Por qué es inseguro?</strong></p><ul><li class=\"ql-align-justify\">&nbsp;&nbsp;Transmite todo en texto plano, incluyendo el usuario y la contraseña.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;Cualquier persona conectada a la misma red puede interceptar esa información.</li><li class=\"ql-align-justify\">  En su lugar debería usarse el protocolo SSH (Secure Shell), que cifra la sesión y protege las  credenciales.</li></ul><p class=\"ql-align-justify\">🖨️<strong>SMB (Server Message Block)</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;<strong>&nbsp;¿Qué es?</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;Protocolo para compartir archivos e impresoras en redes Windows.</p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;<strong>¿Por qué es inseguro?</strong></p><ul><li class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;Versiones antiguas como SMBv1 debido a que tienen múltiples fallas.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;No incluye cifrado por defecto.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;Ya no recibe soporte técnico.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;En su lugar debería usarse la version SMBv3, que incluye autenticación mejorada y cifrado opcional.</li></ul><p class=\"ql-align-justify\">🛜<strong> SNMP (Simple Network Management Protocol)</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;<strong>¿Qué es?</strong></p><p class=\"ql-align-justify\">&nbsp;&nbsp;&nbsp;Se usa para monitorear impresoras, switches, routers y algunos dispositivos de red.</p><p class=\"ql-align-justify\">&nbsp;&nbsp;<strong>¿Por qué es inseguro?</strong></p><ul><li class=\"ql-align-justify\">&nbsp;&nbsp;Versiones como SNMPv1 y SNMPv2c transmiten información crítica sin cifrar.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;Las credenciales de lectura (“community strings”) muchas veces son “public” o “private”, la idea es que estos sean cambiados.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;Puede ser explotado para obtener información del inventario de red.</li><li class=\"ql-align-justify\">&nbsp;&nbsp;En su lugar debería usarse la version SNMPv3, que permite cifrado y autenticación segura.</li></ul><p class=\"ql-align-justify\">🏬 <strong>En nuestra empresa:</strong></p><p class=\"ql-align-justify\">✅ Solo están autorizadas conexiones seguras con protocolos cifrados.</p><p class=\"ql-align-justify\">✅ Se prohíbe el uso de Telnet, SMBv1 y SNMPv1/v2c en Foundever.</p><p class=\"ql-align-justify\">✅ Recuerda que para poder acceder remotamente el usuario final debe estar conectado a la VPN.</p><p class=\"ql-align-justify\">✅ Si ves un sitio con el aviso “Conexión no segura”, no ingreses credenciales.</p>', '2025-07-20 03:31:51', '2025-07-22 05:12:49', NULL),
('security-practices-001', 'Política USB', '<p>💡<strong>¿Sabías que…?</strong></p><p>El 90% de las brechas de seguridad en ocasiones son por errores humanos, como abrir un correo sospechoso o utilizar contraseñas débiles.</p><p>Usar la misma contraseña para varias cuentas es como tener una única llave para todas las puertas de la casa. Si alguna persona logra conseguir esa llave, puede acceder a toda la casa.</p><p>Actualizar el computador no solo mejora el rendimiento, sino que también cierra las puertas donde los hackers pueden aprovechar para ingresar y robar información o sabotear el rendimiento del sistema.</p><p>Las redes de WiFi públicas son espacios abiertos en los que cualquier persona puede llegar a escuchar y observar las acciones de los usuarios que estén conectados a esta, por lo cual se debe tener cuidado a las redes que nos conectamos.</p><p>⚠️ <strong>Riesgos comunes para el computador</strong></p><ul><li>Descargar programas o archivos de sitios no confiables. Recuerda que descargar programas es prohibido.</li><li>Ataques a través de correos falsos que parecen venir de la empresa.</li></ul><p>🛡️ <strong>Consejos para protegerte</strong></p><ul><li>Utiliza contraseñas largas y diferentes para cada cuenta.</li><li>Mantén siempre actualizados los programas y sistema operativo.</li><li>No abras correos o enlaces sospechosos.</li></ul>', '2025-07-07 14:43:02', '2025-07-31 15:53:18', NULL),
('usb-policy-001', 'Seguridad USB y Descargas', '<p>🔌 <strong>¿Qué tan peligrosos son las USB y las descargas?</strong></p><p>El uso de dispositivos externos (como memorias USB) o la descarga de archivos y aplicaciones no autorizadas representan una ruta directa de infección para malware, robo de datos y sabotaje interno, sin mostrar alerta alguna.</p><p>🦠 <strong>Malware por USB</strong></p><p>Las USB pueden contener virus, troyanos, keyloggers o incluso ejecutables que son activados automáticamente al conectarla, infectando el equipo sin necesidad de que se deba abrir algun archivo.</p><p>📥<strong> Aplicaciones o archivos descargados de internet</strong></p><p>&nbsp;&nbsp;&nbsp;Las descargas de sitios web no oficiales pueden contener software malicioso el cual se camufla como instaladores legítimos.</p><p>&nbsp;&nbsp;&nbsp;Algunos archivos con extensiones .exe, .bat, .msi, .scr o .zip descargados sin revisión pueden ejecutar consigo código malicioso.</p><p>🏢 <strong>Política empresarial en Foundever</strong></p><p>✅ Está terminantemente prohibido conectar dispositivos USB personales a equipos corporativos.</p><p>✅ Las descargas de archivos o aplicaciones deben hacerse con ayuda del área de TI.</p><p>❌ El incumplimiento de estas políticas puede ser considerado una falta grave, y puede comprometer no solo la seguridad, sino la continuidad del negocio.</p><p>💡 <strong>Recuerda</strong></p><p>&nbsp;&nbsp;&nbsp;- No conectes ningún USB que no haya sido autorizado.</p><p>&nbsp;&nbsp;&nbsp;- Nunca descargues aplicaciones desde los navegadores, revisa muy bien los archivos que descargas.</p>', '2025-07-07 06:36:27', '2025-07-31 15:31:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `UUID_employees` uuid NOT NULL DEFAULT uuid(),
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `ADP` int(8) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `position` varchar(200) NOT NULL,
  `username` varchar(30) NOT NULL,
  `UUID_users` char(36) NOT NULL DEFAULT uuid()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`UUID_employees`, `first_name`, `last_name`, `ADP`, `status`, `created_at`, `position`, `username`, `UUID_users`) VALUES
('c9658368-6e15-11f0-82d3-5254007e02a0', 'Michael', 'Pineda', 1340296, 'Activo', '2025-07-31 13:53:50', 'Learning Specialist', 'LAT\\mpine085', '27779074-6e13-11f0-82d3-5254007e02a0'),
('436cba51-6e18-11f0-82d3-5254007e02a0', 'Juan', 'Quintero', 1402735, 'Activo', '2025-07-31 14:11:33', 'Agent', 'LAT\\jquin220', '27779074-6e13-11f0-82d3-5254007e02a0'),
('a38fbd90-6e19-11f0-82d3-5254007e02a0', 'Aldair', 'Sandoval', 1809156, 'Activo', '2025-07-31 14:21:24', 'Agent', 'LAT\\asand197', '27779074-6e13-11f0-82d3-5254007e02a0'),
('a50af9ee-6e1b-11f0-82d3-5254007e02a0', 'Alejandra', 'Gomez', 1775751, 'Activo', '2025-07-31 14:35:46', 'Agent', 'LAT\\agome391', '27779074-6e13-11f0-82d3-5254007e02a0'),
('82cd2163-6e1c-11f0-82d3-5254007e02a0', 'Laura', 'Rodriguez', 1375337, 'Activo', '2025-07-31 14:41:58', 'Sr Specialist Recruiting', 'LAT\\lrodr276', '27779074-6e13-11f0-82d3-5254007e02a0'),
('123f1efc-6e1d-11f0-82d3-5254007e02a0', 'Brandon', 'Garcia', 1720414, 'Activo', '2025-07-31 14:45:58', 'Agent', 'LAT\\bgarc140', '27779074-6e13-11f0-82d3-5254007e02a0'),
('80b7f973-6e1d-11f0-82d3-5254007e02a0', 'William', 'Beltran', 1303562, 'Activo', '2025-07-31 14:49:04', 'Coach', 'LAT\\wbelt005', '27779074-6e13-11f0-82d3-5254007e02a0'),
('98e31ffd-6e1e-11f0-82d3-5254007e02a0', 'Julian', 'Lazaro', 1210063, 'Activo', '2025-07-31 14:56:54', 'FPandA Analyst', 'LAT\\jlaza028', '27779074-6e13-11f0-82d3-5254007e02a0'),
('20d4353f-6e1f-11f0-82d3-5254007e02a0', 'Carolina', 'Carbonell', 1487267, 'Activo', '2025-07-31 15:00:42', 'Agent', 'AMER\\carbonca1', '27779074-6e13-11f0-82d3-5254007e02a0'),
('93a86c6a-6e1f-11f0-82d3-5254007e02a0', 'Valentina', 'Torres', 1900020, 'Activo', '2025-07-31 15:03:54', 'Intern', 'LAT\\vtorr082', '27779074-6e13-11f0-82d3-5254007e02a0'),
('381779c0-6e20-11f0-82d3-5254007e02a0', 'Mildred', 'Cervantes', 1461983, 'Activo', '2025-07-31 15:08:30', 'Payroll', 'LAT\\mcerv033', '27779074-6e13-11f0-82d3-5254007e02a0'),
('c5f96693-6e20-11f0-82d3-5254007e02a0', 'Daniel', 'Blum', 1570483, 'Activo', '2025-07-31 15:12:28', 'Agent', 'LAT\\dblum005', '27779074-6e13-11f0-82d3-5254007e02a0'),
('1e837df0-6e21-11f0-82d3-5254007e02a0', 'Alejandra', 'Zaraza', 1396258, 'Activo', '2025-07-31 15:14:57', 'Specialist Recruiting', 'LAT\\azara020', '27779074-6e13-11f0-82d3-5254007e02a0'),
('87aff969-6e21-11f0-82d3-5254007e02a0', 'Karylin', 'Briceno', 1461076, 'Activo', '2025-07-31 15:17:53', 'Coach', 'AMER\\bricenka1', '27779074-6e13-11f0-82d3-5254007e02a0'),
('77075806-6e22-11f0-82d3-5254007e02a0', 'Jefferson', 'Moseton', 791075, 'Activo', '2025-07-31 15:24:35', 'Mgr IT Programming', 'LAT\\jmose007', '27779074-6e13-11f0-82d3-5254007e02a0'),
('73d42b01-6e23-11f0-82d3-5254007e02a0', 'Andres', 'Rodriguez', 1701016, 'Activo', '2025-07-31 15:31:39', 'Learning Specialist', 'LAT\\ arodr770', '27779074-6e13-11f0-82d3-5254007e02a0'),
('f29aa7d4-6e23-11f0-82d3-5254007e02a0', 'Luisa', 'Velasco', 1216424, 'Activo', '2025-07-31 15:35:12', 'Leadership Devpt Specialist', 'LAT\\lvela050', '27779074-6e13-11f0-82d3-5254007e02a0'),
('74289f65-6e24-11f0-82d3-5254007e02a0', 'Cristian', 'Toscano', 1455132, 'Activo', '2025-07-31 15:38:49', 'Agent', 'AMER\\toscancd', '27779074-6e13-11f0-82d3-5254007e02a0'),
('e2647aef-6e24-11f0-82d3-5254007e02a0', 'Steven', 'Tapiero', 1005049, 'Activo', '2025-07-31 15:41:54', 'WF Scheduler', 'LAT\\stapi004', '27779074-6e13-11f0-82d3-5254007e02a0'),
('35d530f6-6e25-11f0-82d3-5254007e02a0', 'Natalia', 'Barrera', 827094, 'Activo', '2025-07-31 15:44:14', 'Mgr Learning Site Apprentice', 'LAT\\nbarr016', '27779074-6e13-11f0-82d3-5254007e02a0'),
('c3d2ecbb-6e25-11f0-82d3-5254007e02a0', 'Carlos', 'Doria', 1899126, 'Activo', '2025-07-31 15:48:12', 'Intern', 'LAT\\cdori007', '27779074-6e13-11f0-82d3-5254007e02a0'),
('c722a49f-6e26-11f0-82d3-5254007e02a0', 'Monica', 'Argote', 1195848, 'Activo', '2025-07-31 15:55:27', 'Call Monitoring Specialist', 'LAT\\margo010', '27779074-6e13-11f0-82d3-5254007e02a0'),
('939a2260-6e27-11f0-82d3-5254007e02a0', 'Ana', 'Ramos', 1903133, 'Activo', '2025-07-31 16:01:10', 'Assoc Analyst Accounting', 'LAT\\aramo305', '27779074-6e13-11f0-82d3-5254007e02a0'),
('6ad66ff8-6e29-11f0-82d3-5254007e02a0', 'Julian', 'Castaneda', 1609272, 'Activo', '2025-07-31 16:14:21', 'Real Time Analyst', 'LAT\\jcast728', '27779074-6e13-11f0-82d3-5254007e02a0'),
('0bf7e6f9-6e2a-11f0-82d3-5254007e02a0', 'Lina', 'Hernandez', 1562414, 'Activo', '2025-07-31 16:18:51', 'Agent', 'LAT\\lhern168', '27779074-6e13-11f0-82d3-5254007e02a0'),
('a5030147-6e2a-11f0-82d3-5254007e02a0', 'Jose', 'Orozco', 1570558, 'Activo', '2025-07-31 16:23:08', 'Admin Asst', 'LAT\\joroz027', '27779074-6e13-11f0-82d3-5254007e02a0'),
('1d4eedf0-6e2b-11f0-82d3-5254007e02a0', 'Ivan', 'Pardo', 1261147, 'Activo', '2025-07-31 16:26:30', 'Agent', 'LAT\\ipard006', '27779074-6e13-11f0-82d3-5254007e02a0'),
('69247ed4-6e2c-11f0-82d3-5254007e02a0', 'Jefferson', 'Salinas', 1378299, 'Activo', '2025-07-31 16:35:47', 'Coach', 'LAT\\jsali066', '27779074-6e13-11f0-82d3-5254007e02a0'),
('66da114c-6e2d-11f0-82d3-5254007e02a0', 'Maria', 'Gomez', 1887642, 'Activo', '2025-07-31 16:42:52', 'Agent', 'LAT\\mgome463', '27779074-6e13-11f0-82d3-5254007e02a0'),
('26345172-6e30-11f0-82d3-5254007e02a0', 'Yudy', 'Mesa', 1281885, 'Activo', '2025-07-31 17:02:32', 'Coach', 'LAT\\ ymesa003', '27779074-6e13-11f0-82d3-5254007e02a0'),
('64988897-6e31-11f0-82d3-5254007e02a0', 'Adriana', 'Mendez', 1082715, 'Activo', '2025-07-31 17:11:26', 'Assoc Spec HR', 'LAT\\amend224', '27779074-6e13-11f0-82d3-5254007e02a0'),
('75b27a4f-6e32-11f0-82d3-5254007e02a0', 'Ricardo', 'Garcia', 1327833, 'Activo', '2025-07-31 17:19:05', 'WF Manager Apprentice', 'LAT\\rgarc299', '27779074-6e13-11f0-82d3-5254007e02a0'),
('653b0029-6e33-11f0-82d3-5254007e02a0', 'Miguel', 'Urreste', 1179624, 'Activo', '2025-07-31 17:25:46', 'Real Time Analyst', 'LAT\\murre002', '27779074-6e13-11f0-82d3-5254007e02a0'),
('0664faaf-6e34-11f0-82d3-5254007e02a0', 'Miguel', 'Cepeda', 1662283, 'Activo', '2025-07-31 17:30:17', 'IT General', 'LAT\\admin_mcepe', '27779074-6e13-11f0-82d3-5254007e02a0'),
('09d5ba47-6e35-11f0-82d3-5254007e02a0', 'Kevin', 'Osorio', 1253541, 'Activo', '2025-07-31 17:37:32', 'Mgr CI Apprentice', 'LAT\\kosor009', '27779074-6e13-11f0-82d3-5254007e02a0'),
('86bae758-6e35-11f0-82d3-5254007e02a0', 'Camila', 'Sanabria', 1719805, 'Activo', '2025-07-31 17:41:02', 'Agent', 'LAT\\csana180', '27779074-6e13-11f0-82d3-5254007e02a0'),
('0dd4e3e1-6e39-11f0-82d3-5254007e02a0', 'Miguel', 'Vanegas', 799700, 'Activo', '2025-07-31 18:06:17', 'Sr Engineer IT Systems', 'LAT\\mvane114', '27779074-6e13-11f0-82d3-5254007e02a0'),
('14cab707-6e41-11f0-82d3-5254007e02a0', 'Diego', 'Cruz', 1103470, 'Activo', '2025-07-31 19:03:44', 'Mgr Ops', 'LAT\\dcruz065', '27779074-6e13-11f0-82d3-5254007e02a0'),
('0f45c716-6e42-11f0-82d3-5254007e02a0', 'Sergio', 'Arenas', 1893199, 'Activo', '2025-07-31 19:10:45', 'Intern', 'LAT\\saren013', '27779074-6e13-11f0-82d3-5254007e02a0'),
('a3d36f89-6e42-11f0-82d3-5254007e02a0', 'Laura', 'Nino', 1075982, 'Activo', '2025-07-31 19:14:54', 'Coach', 'LAT\\lnino004', '27779074-6e13-11f0-82d3-5254007e02a0'),
('f4278ea5-6e42-11f0-82d3-5254007e02a0', 'Samir', 'Ojeda', 8049177, 'Activo', '2025-07-31 19:17:09', 'Agent', 'LAT\\sojed017', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('6ef1f589-6e43-11f0-82d3-5254007e02a0', 'Victor', 'Diaz', 1739713, 'Activo', '2025-07-31 19:20:35', 'Intern', 'LAT\\vdiaz063', '27779074-6e13-11f0-82d3-5254007e02a0'),
('0ed4838b-6e44-11f0-82d3-5254007e02a0', 'Wendy', 'Camacho', 1454344, 'Activo', '2025-07-31 19:25:03', 'Accounting', 'LAT\\wcama006', '27779074-6e13-11f0-82d3-5254007e02a0'),
('1877e895-6e44-11f0-82d3-5254007e02a0', 'Aillyng', 'Ariza', 1454412, 'Activo', '2025-07-31 19:25:19', 'Coach', 'aillyng.ariza@sitel.com', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('796b9978-6e44-11f0-82d3-5254007e02a0', 'Camilo', 'Camargo', 1454449, 'Activo', '2025-07-31 19:28:02', 'Systems Support', 'LAT\\ccama055', '27779074-6e13-11f0-82d3-5254007e02a0'),
('00d30f07-6e45-11f0-82d3-5254007e02a0', 'Andres', 'Astorga', 1584388, 'Activo', '2025-07-31 19:31:49', 'Analyst IT Systems', 'LAT\\aasto008', '27779074-6e13-11f0-82d3-5254007e02a0'),
('853cbe0a-6e45-11f0-82d3-5254007e02a0', 'Nancy', 'Morales', 1387127, 'Activo', '2025-07-31 19:35:31', 'Specialist Recruiting', 'LAT\\nmora071', '27779074-6e13-11f0-82d3-5254007e02a0'),
('ac85b6f4-6e45-11f0-82d3-5254007e02a0', 'Laura', 'Hernandez', 1714822, 'Activo', '2025-07-31 19:36:37', 'Mgr Learning Site', 'AMER\\hernanld4', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('668d9616-6e46-11f0-82d3-5254007e02a0', 'Wenndy', 'Sanchez', 1385988, 'Activo', '2025-07-31 19:41:49', 'Call Monitoring Specialist', 'LAT\\wsanc018', '27779074-6e13-11f0-82d3-5254007e02a0'),
('c5db317b-6e46-11f0-82d3-5254007e02a0', 'Paula', 'Venecia', 1546254, 'Activo', '2025-07-31 19:44:29', 'Agent', 'LAT\\pvene010', '27779074-6e13-11f0-82d3-5254007e02a0'),
('42a658c0-6e47-11f0-82d3-5254007e02a0', 'Maria', 'Acosta', 1371296, 'Activo', '2025-07-31 19:47:58', 'Real Time Analyst', 'LAT\\macos045', '27779074-6e13-11f0-82d3-5254007e02a0'),
('9ed8a511-6e47-11f0-82d3-5254007e02a0', 'Alfredo', 'Aaron', 1580731, 'Activo', '2025-07-31 19:50:33', 'IT Systems Specialist', 'LAT\\admin_aaaro009', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('eb1452be-6e47-11f0-82d3-5254007e02a0', 'Aldemar', 'Aleman', 1622688, 'Activo', '2025-07-31 19:52:41', 'Agent', 'AMER\\alemanal', '27779074-6e13-11f0-82d3-5254007e02a0'),
('75b31fe3-6e48-11f0-82d3-5254007e02a0', 'Roberto', 'Diaz', 1357682, 'Activo', '2025-07-31 19:56:33', 'CI Analyst', 'LAT\\rdiaz087', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('b821f965-6e48-11f0-82d3-5254007e02a0', 'Rodolfo', 'Onzaga', 1361541, 'Activo', '2025-07-31 19:58:25', 'IT Systems Specialist', 'LAT\\admin_ronza', '27779074-6e13-11f0-82d3-5254007e02a0'),
('1ce22816-6e49-11f0-82d3-5254007e02a0', 'Jaison', 'Nieto', 1699842, 'Activo', '2025-07-31 20:01:14', 'Agent', 'LAT\\jniet035', '27779074-6e13-11f0-82d3-5254007e02a0'),
('2b9f4efb-6e49-11f0-82d3-5254007e02a0', 'Daniel', 'Coronado', 1533282, 'Activo', '2025-07-31 20:01:39', 'Agent', 'LAT\\dcoro017', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('e51bd131-6e49-11f0-82d3-5254007e02a0', 'Milton', 'Depablos', 741838, 'Activo', '2025-07-31 20:06:50', 'IT Systems Specialist', 'LAT\\admin_mdepa', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('9068933c-6e4a-11f0-82d3-5254007e02a0', 'Cristian', 'Blanco', 1562412, 'Activo', '2025-07-31 20:11:37', 'Learning Specialist', 'cristian.blanco@sitel.com', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('c9f6ee92-6e4a-11f0-82d3-5254007e02a0', 'Glenda', 'Henriquez', 1011185, 'Activo', '2025-07-31 20:13:14', 'Specialist Payroll', 'LAT\\ghenr008', '27779074-6e13-11f0-82d3-5254007e02a0'),
('10972f66-6e4b-11f0-82d3-5254007e02a0', 'Nicoll', 'Vargas', 1583739, 'Activo', '2025-07-31 20:15:12', 'Analyst IT Systems', 'LAT\\admin_nvarg', '27779074-6e13-11f0-82d3-5254007e02a0'),
('22a81937-6e4b-11f0-82d3-5254007e02a0', 'Wendy', 'Gomez', 1368489, 'Activo', '2025-07-31 20:15:43', 'IT Systems Specialist', 'LAT\\admin_wgome', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('4d05a8df-6e4b-11f0-82d3-5254007e02a0', 'Keyner', 'Garcia', 1914397, 'Activo', '2025-07-31 20:16:54', 'Intern', 'LAT\\kgarc249', '27779074-6e13-11f0-82d3-5254007e02a0'),
('b132fe33-6ed9-11f0-82d3-5254007e02a0', 'Dilan', 'Velasquez', 1698130, 'Activo', '2025-08-01 13:16:10', 'Coach', 'LAT\\dvela092', '27779074-6e13-11f0-82d3-5254007e02a0'),
('001bf058-6ee0-11f0-82d3-5254007e02a0', 'Antoni', 'Valero', 1334581, 'Activo', '2025-08-01 14:01:20', 'Coach', 'LAT\\avale140', '27779074-6e13-11f0-82d3-5254007e02a0'),
('1b6e118d-6ee9-11f0-82d3-5254007e02a0', 'Jenny', 'Guerrero', 1278177, 'Activo', '2025-08-01 15:06:31', 'Coach', 'jenny.guerrero@sitel.com', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('37a3d25c-6eec-11f0-82d3-5254007e02a0', 'Jhony', 'Rivera', 1319764, 'Activo', '2025-08-01 15:28:47', 'IT Systems Specialist', 'LAT\\admin_jrive', '27779074-6e13-11f0-82d3-5254007e02a0'),
('e86d9fd3-6eec-11f0-82d3-5254007e02a0', 'Rosa', 'Jimenez', 1744565, 'Activo', '2025-08-01 15:33:43', 'Intern', 'LAT\\rjime116', '27779074-6e13-11f0-82d3-5254007e02a0'),
('7df3c954-6eed-11f0-82d3-5254007e02a0', 'Alexander', 'Montes', 1736110, 'Activo', '2025-08-01 15:37:54', 'Agent', 'AMER\\montesal4', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('c725daf6-6eed-11f0-82d3-5254007e02a0', 'Javier', 'Vega', 1608555, 'Activo', '2025-08-01 15:39:57', 'IT Systems Specialist', 'LAT\\admin_jvega090', '27779074-6e13-11f0-82d3-5254007e02a0'),
('f2ee52ae-6eed-11f0-82d3-5254007e02a0', 'Andres', 'Aldana', 1882167, 'Activo', '2025-08-01 15:41:11', 'Agent', 'LAT\\aalda039', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('c29b1c16-6eee-11f0-82d3-5254007e02a0', 'Camila', 'Rincon', 1278406, 'Activo', '2025-08-01 15:46:59', 'FIN General Accounting', 'LAT\\mrinc016', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('28d29737-6eef-11f0-82d3-5254007e02a0', 'Angelica', 'Roldan', 890537, 'Activo', '2025-08-01 15:49:50', 'Coach', 'LAT\\arold007', '27779074-6e13-11f0-82d3-5254007e02a0'),
('16650b16-6ef0-11f0-82d3-5254007e02a0', 'Carmen', 'Hormaza', 1307251, 'Activo', '2025-08-01 15:56:29', 'Supv Recruiting', 'LAT\\chorm004', '27779074-6e13-11f0-82d3-5254007e02a0'),
('35ba6b57-6ef0-11f0-82d3-5254007e02a0', 'Laura', 'Pumarejo', 1488198, 'Activo', '2025-08-01 15:57:22', 'Agent', 'LAT\\lpuma001', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('d1535cce-6ef0-11f0-82d3-5254007e02a0', 'Lenin', 'Mendoza', 838501, 'Activo', '2025-08-01 16:01:43', 'Director FP A', 'LAT\\lmend148', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('42c6d7db-6ef1-11f0-82d3-5254007e02a0', 'Victor', 'Cabarcas', 1454758, 'Activo', '2025-08-01 16:04:53', 'Coach', 'AMER\\cabarcvh', '27779074-6e13-11f0-82d3-5254007e02a0'),
('37750672-6ef2-11f0-82d3-5254007e02a0', 'Vannesa', 'Prieto', 1487740, 'Activo', '2025-08-01 16:11:44', 'Agent', 'LAT\\vprie008', '27779074-6e13-11f0-82d3-5254007e02a0'),
('c6282f67-6ef2-11f0-82d3-5254007e02a0', 'Erika', 'Peña', 1144276, 'Activo', '2025-08-01 16:15:43', 'Assoc Analyst Accounting', 'LAT\\epena024', '27779074-6e13-11f0-82d3-5254007e02a0'),
('22329cce-6ef3-11f0-82d3-5254007e02a0', 'Andres', 'Bohorquez', 1936210, 'Activo', '2025-08-01 16:18:17', 'Agent', 'LAT\\aboho023', '27779074-6e13-11f0-82d3-5254007e02a0'),
('673812b0-6ef3-11f0-82d3-5254007e02a0', 'Leydy', 'Avila', 1031365, 'Activo', '2025-08-01 16:20:13', 'Supv Payroll', 'LAT\\lavil023', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('f7628fb5-6ef3-11f0-82d3-5254007e02a0', 'Diana', 'Rubio', 1092189, 'Activo', '2025-08-01 16:24:15', 'Assoc Analyst Accounting', 'LAT\\drubi010', '27779074-6e13-11f0-82d3-5254007e02a0'),
('5aeae9f8-6ef4-11f0-82d3-5254007e02a0', 'Emanuel', 'Berdejo', 1748286, 'Activo', '2025-08-01 16:27:02', 'IT', 'LAT\\eberd002', '27779074-6e13-11f0-82d3-5254007e02a0'),
('7a161ab4-6ef4-11f0-82d3-5254007e02a0', 'Gerson', 'Charria', 1773692, 'Activo', '2025-08-01 16:27:54', 'Agent', 'LAT\\gchar037', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('a03ac474-6ef4-11f0-82d3-5254007e02a0', 'Alexander', 'Benavides', 1454621, 'Activo', '2025-08-01 16:28:58', 'Systems Support', 'AMER\\benaviad1', '27779074-6e13-11f0-82d3-5254007e02a0'),
('858724c9-6ef5-11f0-82d3-5254007e02a0', 'Ingrid', 'Castañeda', 1338468, 'Activo', '2025-08-01 16:35:23', 'Agent', 'LAT\\icast05', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('19752305-6ef6-11f0-82d3-5254007e02a0', 'Daniela', 'Ferrer', 1003538, 'Activo', '2025-08-01 16:39:31', 'Mgr Learning Site Apprentice', 'LAT\\dferr022', '98fc71c1-6e19-11f0-82d3-5254007e02a0'),
('8f0f1c84-6ef6-11f0-82d3-5254007e02a0', 'Andres', 'Gonzalez', 1749239, 'Activo', '2025-08-01 16:42:48', 'Intern', 'LAT\\agonz919', '98fc71c1-6e19-11f0-82d3-5254007e02a0');

-- --------------------------------------------------------

--
-- Table structure for table `employees_report`
--

CREATE TABLE `employees_report` (
  `UUID_report` uuid NOT NULL DEFAULT uuid(),
  `priority` varchar(20) NOT NULL,
  `date_incident` date NOT NULL,
  `type` varchar(300) NOT NULL,
  `ticket` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `date_resolved` date DEFAULT NULL,
  `UUID_employees` uuid NOT NULL DEFAULT uuid(),
  `intervention_area` varchar(300) NOT NULL,
  `description_area` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees_report`
--

INSERT INTO `employees_report` (`UUID_report`, `priority`, `date_incident`, `type`, `ticket`, `status`, `description`, `date_resolved`, `UUID_employees`, `intervention_area`, `description_area`) VALUES
('c6a2c77d-6e17-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-09-19', 'Add Device \"samsungSM-A556E\"', 12487495, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-09-24', 'c9658368-6e15-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('2f96696d-6e19-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-09-25', 'Malware - Trojan', 12517896, 'Cerrado', 'Malware Protection encontró una indicación de compromiso. Encontrado el host CO723P-REA-71H9 activando alertas relacionadas con malware.\nC:\\Program Files\\Cisco\\AMP\\clamav\\0.103.2.19\\temp\\clamtmp\\clamav-b5e50be0665a0075b99b31821ed99ffc.tmp\\html-tmp.656a7f6200\\notags.html', '2024-09-27', '436cba51-6e18-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin.'),
('264874bc-6e1a-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-02', 'Add Device \"LAPTOP-TGKQG0VK\"', 12553715, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de su organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos dentro de la organización.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-10-03', 'a38fbd90-6e19-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('3f1421d3-6e1c-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-03', 'Cyber Threat', 12556749, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-10-03', 'a50af9ee-6e1b-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('c25fbee4-6e1c-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-03', 'Add Device \"XiaomiM2003J15SC\"', 12560487, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de su organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos dentro de la organización.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-10-04', '82cd2163-6e1c-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('3abe05e2-6e1d-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-10-15', 'Cyber Threat', 12620738, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-10-16', '123f1efc-6e1d-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('104abb76-6e1e-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-10', 'Add Device \"William\"', 12598210, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-10-16', '80b7f973-6e1d-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('e80c9543-6e1e-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-12', 'Add Device \"Julian\"', 12604183, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-10-16', '98e31ffd-6e1e-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('4d89bad5-6e1f-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-18', 'Cyber Threat', 12637505, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-10-22', '20d4353f-6e1f-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('f2b2961d-6e1f-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-10-23', 'Privilege Escalation', 12659718, 'Cerrado', 'Acceso inicial, Acceso a credenciales, Desconocido/Otros, Escalada de privilegios.', '2024-10-20', '93a86c6a-6e1f-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('8cb4636f-6e20-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-10-20', 'Fraudulent DUO', 12642632, 'Cerrado', 'Esta alerta se dispara cuando un usuario selecciona «Denegar» y elige reportar el intento como fraude en lugar de un error en la Autenticación DUO.', '2024-10-23', '381779c0-6e20-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Crhistian Marin'),
('e74ef6a3-6e20-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-03', 'Cyber Threat', 12717778, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-11-06', 'c5f96693-6e20-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('5790349c-6e21-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-08', 'Add Device \"Aleza\"', 12744444, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-11-09', '1e837df0-6e21-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('30351d34-6e22-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-04', 'Compromised Password', 12722611, 'Cerrado', 'Los siguientes usuarios tienen contraseñas que pueden ser fácilmente comprometidas, por favor, haga un seguimiento con ellos para restablecer su contraseña de inmediato con una más compleja.', '2024-11-05', '87aff969-6e21-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Camilo Camargo'),
('3e9148bb-6e23-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-05', 'Brute-force', 12729609, 'Cerrado', 'Detecta una serie de inicios de sesión fallidos seguidos de un inicio de sesión exitoso. Esto podría indicar que un atacante ha conseguido adivinar la contraseña de un usuario y ha comprometido su cuenta. Esta regla no aprovecha los registros de autenticación con una acción normalizada de inicio de sesión de dominio y no tiene una versión de dominio, lo que significa que se requiere el registro de estaciones de trabajo de Windows para lograr una visibilidad completa. Nombre de host \"CO723L-IT-3UX4, US145-TAS-PSN01, US200-TAS-PSN01, US0145LAPP01\".', '2024-11-06', '77075806-6e22-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('ae1461fa-6e23-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-06', 'Add Device \"Andres\"', 12736637, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-11-07', '73d42b01-6e23-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('3ec7a3f9-6e24-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-11', 'Add Device \"DESKTOP-8AVT02D\"', 12758887, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas internas de Spearphishing a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar una inundación de agotamiento de servicio en un inquilino de Azure AD mediante el registro de un gran número de dispositivos.', '2024-11-12', 'f29aa7d4-6e23-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('ae3110a6-6e24-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-12', 'Authentication From OFAC', 12763982, 'Cerrado', 'Esta primera regla vista se activa cuando el mismo usuario intenta autenticarse en Azure desde países OFAC. Esta actividad indica un posible robo de credenciales.', '2024-11-13', '74289f65-6e24-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('064e9143-6e25-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-13', 'Authentication From OFAC', 12767092, 'Cerrado', 'Esta primera regla vista se activa cuando el mismo usuario intenta autenticarse en Azure desde países OFAC. Esta actividad indica un posible robo de credenciales. IP 188.130.219.121', '2024-11-13', 'e2647aef-6e24-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('981bff28-6e25-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-14', 'Authentication From OFAC', 12777875, 'Cerrado', 'Esta primera regla vista se activa cuando el mismo usuario intenta autenticarse en Azure desde países OFAC. Esta actividad indica un posible robo de credenciales. IP 45.83.119.242', '2024-11-15', '35d530f6-6e25-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('7f61960f-6e26-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-11-18', 'Add Device \"DESKTOP-40S0UJE\"', 12795616, 'Cerrado', 'El usuario inicia sesión con la cuenta de Foundever en un dispositivo personal denominado \"DESKTOP-40S0UJE\"', '2024-11-19', 'c3d2ecbb-6e25-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('61ba0a34-6e27-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-19', 'Add Device \"LAPTOP-BT1VOL7D\"', 12797794, 'Cerrado', 'El usuario inicia sesión desde Dubin, Irlanda con la cuenta de Foundever en un dispositivo personal denominado \"LAPTOP-BT1VOL7D\"', '2024-11-19', 'c722a49f-6e26-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('1ee53c34-6e28-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-19', 'Authentication From Unexpected Country', 12803208, 'Cerrado', 'El usuario inicia sesión desde Dubin, Irlanda con la cuenta de Foundever en un dispositivo personal con la IP 45.154.153.14', '2024-11-20', '939a2260-6e27-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('420826fe-6e29-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-11-27', 'Cyber Threat', 12844230, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-11-27', 'c5f96693-6e20-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('dcdda4c3-6e29-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-02', 'Malware - RILIDE IOC\'s', 12859653, 'Cerrado', 'A New Malicious Browser Extension for Stealing. C:\\Program Files (x86)\\Microsoft\\Edge\\Application', '2024-12-03', '6ad66ff8-6e29-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('6c1b5ec9-6e2a-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-12-03', 'Cyber Threat', 12873997, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-12-04', '0bf7e6f9-6e2a-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('f175f175-6e2a-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-06', 'Malware - RILIDE IOC\'s', 12888840, 'Cerrado', 'Trustwave SpiderLabs ha descubierto una nueva cepa de malware a la que ha bautizado como Rilide, dirigida a navegadores basados en Chromium como Google Chrome, Microsoft Edge, Brave y Opera. C:\\Program Files\\Google\\Chrome\\Application', '2024-12-06', 'a5030147-6e2a-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('1faf3ffd-6e2c-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-06', 'Malware - RILIDE IOC\'s', 12888312, 'Cerrado', 'Trustwave SpiderLabs ha descubierto una nueva cepa de malware a la que ha bautizado como Rilide, dirigida a navegadores basados en Chromium como Google Chrome, Microsoft Edge, Brave y Opera. CO723P-5F0FNF3 (RILIDE Malware IOC\'s). C:\\Program Files (x86)\\Microsoft\\Edge\\Application', '2024-12-01', '1d4eedf0-6e2b-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('4dce71a4-6e2e-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-07', 'Preauth Failure DUO', 12893871, 'Cerrado', 'Esta regla pretende detectar la omisión exitosa de Cisco Duo MFA en el inicio de sesión VPN. Estas actividades pueden indicar que actores maliciosos han comprometido la contraseña de una cuenta, pero no el token MFA correspondiente, y tales actividades deben ser auditadas para comprobar su legitimidad.', '2024-12-09', '66da114c-6e2d-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('3957e7e9-6e31-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-12-09', 'Add Device \"STLC-1MRHH63\"', 12898235, 'Cerrado', 'El usuario inicia sesión desde Dubin, Irlanda con la cuenta de Foundever en un dispositivo personal con el hostname \"STLC-1MRHH63\"', '2024-12-09', '26345172-6e30-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('5123cb87-6e32-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-09', 'Fraudulent DUO', 12904429, 'Cerrado', 'Devuelve los eventos en los que se denegó la autenticación porque el usuario final marcó explícitamente \"fraudulento\".', '2024-12-12', '64988897-6e31-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('2040daaa-6e33-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-12-11', 'Spider  - Scattered IOC\'s', 12917509, 'Cerrado', 'Detecta si una estación de trabajo conectada a Scattered Spider IOC. La dirección IP remota detectada fue marcada como maliciosa por verificadores de reputación IP en línea y es uno de los IOC de scattered spider. Tenga en cuenta que el intento de conexión fue descartado por Checkpoint.', '2024-12-12', '75b27a4f-6e32-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('cc9a8e93-6e33-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-12', 'Add Device \"t800\"', 12923383, 'Cerrado', 'Añadir propietario registrado al dispositivo \"t800\"', '2024-12-13', '653b0029-6e33-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e5ddfb0e-6e34-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-12-17', 'SUSPICIOUS VBSCRIPT', 12946101, 'Cerrado', 'Este IOC identifica el uso de declaraciones explícitas de motor de script para cscript o wscript sin sus extensiones de archivo normalmente asociadas. C:\\Users\\admin_mcepe016\\Downloads\\amd-chipset-drivers-6-01-25-342.exe', '2024-12-18', '0664faaf-6e34-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('5fa2839e-6e35-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-18', 'Add Device', 12951148, 'Cerrado', 'El usuario inicia sesión desde Roubaix, Nord, France con la cuenta de Foundever y la IP 139.28.87.140', '2024-12-18', '09d5ba47-6e35-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('ea1bbfab-6e35-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2024-12-20', 'Cyber Threat', 12960407, 'Cerrado', 'El equipo de Cyber Threat Intel detectó datos confidenciales compartidos en la dark web para la(s) siguiente(s) cuenta(s).', '2024-12-20', '86bae758-6e35-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e639d010-6e40-11f0-82d3-5254007e02a0', 'P3 - Media', '2024-12-20', 'Malware - Trojan and PUP', 12963546, 'Cerrado', 'Malware Protection encontró una indicación de compromiso. E:\\Miguel\\Programas\\Hirens-Boot\\Hirens.BootCD.15.1.zip, E:\\Miguel\\Programas\\XP_Sp3_uE_-_Bj_-_Spanish.iso', '2024-12-21', '0dd4e3e1-6e39-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e4e0e823-6e41-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-01-04', 'BIRDBAIT', 13008625, 'Cerrado', 'Un análisis más detallado reveló que la actividad está asociada a un gusano basado en USB que modifica los accesos directos de Windows para ejecutar el proceso de línea de comandos con entradas maliciosas. La ejecución del instalador de Windows se inició desde un dispositivo USB infectado que contenía un descargador basado en LNK. Mandiant rastrea este descargador basado en LNK como BIRDBAIT.', '2025-01-04', '14cab707-6e41-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('730019ae-6e42-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-01-08', 'Account Set to Don\'t Expire Password', 13031565, 'Cerrado', 'Cuenta de usuario de Windows configurada para que no caduque la contraseña.', '2025-01-09', '0f45c716-6e42-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('49c6fb9e-6e43-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-09', 'Brute-force', 13037606, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-09', 'a3d36f89-6e42-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('7f93485d-6e43-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-06-12', 'Passwords compromised', 13816583, 'Cerrado', 'Los siguientes usuarios tienen contraseñas que pueden ser fácilmente comprometidas', '2025-06-16', 'f4278ea5-6e42-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e5f23436-6e43-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-09', 'Brute-force', 13036062, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-09', '6ef1f589-6e43-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('43bee97e-6e44-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-09', 'Brute-force', 13037458, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-10', '0ed4838b-6e44-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('b0995a40-6e44-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-06-09', 'Spike Login Failures from a Device', 13800228, 'Cerrado', 'Detecta intentos de inicio de sesión fallidos excesivos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Esto está diseñado para detectar tanto ataques de fuerza bruta lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-06-12', '1877e895-6e44-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('ca76c878-6e44-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-09', 'Brute-force', 13038036, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-10', '796b9978-6e44-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('4c162b6d-6e45-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-09', 'Brute-force', 13038046, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-10', '00d30f07-6e45-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('2e8aa5b1-6e46-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-10', 'Fraudulent DUO', 13041647, 'Cerrado', 'Esta alerta se activa cuando un usuario selecciona \"Denegar\" y decide revocar el intento como fraude en lugar de como error.', '2025-01-10', '853cbe0a-6e45-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('92b6c177-6e46-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-06-10', 'Passwords compromised', 13805870, 'Cerrado', 'El equipo de Inteligencia sobre amenazas cibernéticas detectó que se estaban compartiendo datos confidenciales en la web oscura para las siguientes cuentas.', '2025-06-10', 'ac85b6f4-6e45-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('9eeecc16-6e46-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-01-13', 'Brute-force', 13051171, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-13', '668d9616-6e46-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('10ef1061-6e47-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-13', 'Cyber Threat', 13050951, 'Cerrado', 'Cyber Threat Intel Team detectó datos confidenciales compartidos en la red oscura para las siguientes cuentas.', '2025-01-13', 'c5db317b-6e46-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('bafb4c45-6e47-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-13', 'Cyber Threat', 13051056, 'Cerrado', 'Cyber Threat Intel Team detectó datos confidenciales compartidos en la red oscura para las siguientes cuentas', '2025-01-13', '42a658c0-6e47-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('2454d3d1-6e48-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-05-28', 'Privileged Windows Account was Enabled', 13742829, 'Cerrado', 'Las cuentas desactivadas pueden ser reactivadas por usuarios legítimos o, en caso de compromiso, por actores maliciosos. Incluso los usuarios administradores legítimos, si se ven comprometidos o actúan de forma maliciosa, pueden reactivar cuentas para realizar acciones no autorizadas. Las cuentas de administrador tienen acceso a potentes funcionalidades del sistema, y su reactivación podría utilizarse para elevar privilegios con el fin de realizar actividades maliciosas adicionales. Una cuenta de administrador comprometida podría utilizarse como punto de apoyo para moverse lateralmente dentro de una red y acceder a otros recursos críticos.', '2025-06-05', '9ed8a511-6e47-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('70dbe5cb-6e48-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-13', 'Malware - Phishing', 13047503, 'Cerrado', 'Malware Protection encontró una indicación de compromiso.\nGeneric.HTML.Phishing.V.910E6DEE y Generic.HTML.Phishing.V.B0CED052 en C:\\Users\\alemanal\\AppData\\Local\\Google\\Chrome\\User Data\\Default\\Cache\\Cache_Data', '2025-01-16', 'eb1452be-6e47-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Miguel Cepeda'),
('dec8f0fb-6e48-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-05-29', 'Passwords compromised', 13743067, 'Cerrado', 'El equipo de inteligencia sobre amenazas cibernéticas detectó que se estaban compartiendo datos confidenciales en la dark web para las siguientes cuentas.', '2025-05-30', '75b31fe3-6e48-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('f6faff01-6e48-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-14', 'ThinScale - Unauthorized', 13054712, 'Cerrado', 'Se ha identificado un fallo múltiple de autenticación en ThinScale Management Console desde una conexión de base de datos local.', '2025-01-14', 'b821f965-6e48-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('701aee6e-6e49-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-17', 'Cyber Threat', 13077573, 'Cerrado', 'Cyber Threat Intel Team detectó datos confidenciales compartidos en la web oscura para la(s) siguiente(s) cuenta(s).', '2025-01-17', '1ce22816-6e49-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('9075e3f2-6e49-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-05-26', 'Passwords compromised', 13725855, 'Cerrado', 'El equipo de Inteligencia sobre Amenazas Cibernéticas detectó que se estaban compartiendo datos confidenciales en la dark web para las siguientes cuentas:', '2025-05-29', '2b9f4efb-6e49-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('4b2b49bf-6e4a-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-05-19', 'Windows User Account Set to Don\'t Expire Password', 13692546, 'Cerrado', 'Cuenta de usuario de Windows configurada para que la contraseña no caduque', '2025-05-22', 'e51bd131-6e49-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('6fdbb434-6e4a-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-01-21', 'DNS File Transfer', 13093336, 'Cerrado', 'Esta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de filtración de datos. Los agresores pueden utilizar un servicio web externo legítimo para filtrar datos en lugar de su canal de mando y control principal.', '2025-01-22', '6ef1f589-6e43-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e3a11c55-6e4a-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-22', 'DNS File Transfer', 13103237, 'Cerrado', 'Esta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de filtración de datos. Los agresores pueden utilizar un servicio web externo legítimo para filtrar datos en lugar de su canal de mando y control principal.', '2025-01-22', 'c9f6ee92-6e4a-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('e7578c07-6e4a-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-05-20', 'Authentication From Unexpected Country: US', 13702742, 'Cerrado', 'Regla «First Seen» (Primera vez visto) que se activa cuando hay al menos dos inicios de sesión correctos del mismo usuario con códigos de país diferentes, lo que indica un posible robo de credenciales.', '2025-05-22', '9068933c-6e4a-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('247b728e-6e4b-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-27', 'ThinScale - Unauthorized', 13121049, 'Cerrado', 'Se ha identificado un fallo múltiple de autenticación en ThinScale Management Console desde una conexión de base de datos local.', '2025-01-27', '10972f66-6e4b-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('63a5ec13-6e4b-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-29', 'Brute-force', 13128037, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-29', '4d05a8df-6e4b-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('73345a23-6e4b-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-05-19', 'Windows User Account Set to Don\'t Expire Password', 13695719, 'Cerrado', 'Cuenta de usuario de Windows configurada para que la contraseña no caduque', '2025-05-20', '22a81937-6e4b-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('612a51cb-6ee2-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-28', 'Brute-force', 13134374, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-29', '001bf058-6ee0-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('28adb974-6ee9-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-01-28', 'Brute-force', 13128348, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-29', 'b132fe33-6ed9-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('8f030ace-6eeb-11f0-82d3-5254007e02a0', 'P4 - Baja', '2025-05-17', 'Authentication From Unexpected Country', 13685749, 'Cerrado', 'Regla «First Seen» que se activa cuando hay al menos dos inicios de sesión exitosos del mismo usuario con códigos de país diferentes, lo que indica un posible robo de credenciales.', '2025-05-20', '1b6e118d-6ee9-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('b059fd87-6eeb-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-29', 'ThinScale - Failed', 13134931, 'Cerrado', 'Se ha identificado un fallo múltiple de autenticación en ThinScale Management Console desde una conexión de base de datos local.', '2025-01-29', 'b821f965-6e48-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('55c3abbb-6eec-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-29', 'ThinScale - Failed', 13134918, 'Cerrado', 'Se ha identificado un fallo múltiple de autenticación en ThinScale Management Console desde una conexión de base de datos local.', '2025-01-29', '37a3d25c-6eec-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('065de010-6eed-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-01-31', 'Brute-force', 13148158, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-01-31', 'e86d9fd3-6eec-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('ac655cda-6eed-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-05-09', 'Passwords compromised', 13649528, 'Cerrado', 'Los siguientes usuarios tienen contraseñas que pueden ser fácilmente comprometidas.', '2025-05-12', '7df3c954-6eed-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Miguel Cepeda'),
('e0dd634f-6eed-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-02-03', 'Brute-force', 13157034, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-02-04', 'c725daf6-6eed-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('79e36491-6eee-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-04-28', 'Passwords compromised', 13585399, 'Cerrado', 'El equipo de inteligencia sobre amenazas cibernéticas detectó que se estaban compartiendo datos confidenciales en la web oscura para las siguientes cuentas.', '2025-05-02', 'f2ee52ae-6eed-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('392a3575-6eef-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-02-04', 'Brute-force', 13169984, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-02-05', '28d29737-6eef-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('edc01f76-6eef-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-04-21', 'Fraudulent DUO', 13557754, 'Cerrado', 'Devuelve los eventos en los que se denegó la autenticación porque el usuario final marcó explícitamente «fraudulento».', '2025-04-28', 'c29b1c16-6eee-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('5e4a286d-6ef0-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-02-07', 'Add Device', 13183415, 'Cerrado', 'Detecta un usuario que añade un dispositivo en Azure. En estos escenarios, un usuario puede acceder a los recursos de nuestra organización utilizando un dispositivo personal. Los dispositivos registrados en Azure AD pueden ser capaces de llevar a cabo campañas de Spearphishing Interno a través de correos electrónicos intra-organizacionales.\n\nAdemás, un adversario puede ser capaz de realizar un Service Exhaustion Flood en un tenant de Azure AD registrando un gran número de dispositivos.', '2025-02-07', '16650b16-6ef0-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('86229696-6ef0-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-04-22', 'Spike Login Failures VPN', 13557843, 'Cerrado', 'Detecta intentos de inicio de sesión fallidos excesivos para el usuario basándose en una desviación estándar diaria atípica para dicho nombre de usuario. Esto está diseñado para detectar tanto ataques de fuerza bruta lentos como rápidos utilizando una referencia histórica específica del nombre de usuario.', '2025-04-28', '35ba6b57-6ef0-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('b70f0fbd-6ef0-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-02-10', 'Brute-force', 13194565, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-02-10', '668d9616-6e46-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('0df99608-6ef1-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-02-13', 'ThinScale - Failed', 13214288, 'Cerrado', 'Se ha identificado un fallo múltiple de autenticación en ThinScale Management Console desde una conexión de base de datos local.', '2025-02-13', '9ed8a511-6e47-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('1f85ac35-6ef1-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-04-15', 'Fraudulent DUO', 13532992, 'Cerrado', 'Esta alerta se activa cuando un usuario selecciona «Denegar» y decide denunciar el intento como fraude en lugar de como error.', '2025-04-22', 'd1535cce-6ef0-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('d77b6ecf-6ef1-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-02-14', 'Passwords compromised', 13225773, 'Cerrado', 'A continuación, los usuarios tienen contraseñas que pueden ser fácilmente comprometidas.', '2025-02-17', '42c6d7db-6ef1-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('6213eda0-6ef2-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-02-27', 'Spike Login Failures VPN', 13283967, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el usuario basándose en una desviación estándar diaria atípica para dicho nombre de usuario. Esto está diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del nombre de usuario.', '2025-02-27', '37750672-6ef2-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('9f572020-6ef2-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-05', 'Spike Login Failures VPN', 13313700, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-03-05', '37750672-6ef2-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('f6ba13e0-6ef2-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-03-07', 'Fraudulent DUO', 13330764, 'Cerrado', 'Esta alerta se activa cuando un usuario selecciona \"Denegar\" y decide revocar el intento como fraude en lugar de como error.', '2025-03-07', 'c6282f67-6ef2-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres.'),
('4c274eb3-6ef3-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-07', 'Spike Login Failures VPN', 13327208, 'Cerrado', 'Detecta un número excesivo de intentos de inicio de sesión fallidos para el mismo dispositivo basándose en una desviación estándar diaria atípica para dicho dispositivo. Se ha diseñado para detectar ataques de fuerza bruta tanto lentos como rápidos utilizando una línea de base histórica específica del dispositivo.', '2025-03-07', '22329cce-6ef3-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('20f6da79-6ef4-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-04-03', 'DNS File Transfer', 13468088, 'Cerrado', 'Esta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de exfiltración de datos. Los adversarios pueden utilizar un servicio web externo legítimo ya existente para exfiltrar datos en lugar de su canal principal de comando y control. Los servicios web populares que actúan como mecanismo de exfiltración pueden proporcionar una cobertura significativa debido a la probabilidad de que los hosts de una red ya se estén comunicando con ellos antes de ser comprometidos. Es posible que ya existan reglas de firewall que permitan el tráfico a estos servicios.', '2025-04-03', '673812b0-6ef3-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('379a838b-6ef4-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-13', 'DNS File Transfer', 13357645, 'Cerrado', 'Esta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de filtración de datos. Los delincuentes pueden utilizar un servicio web externo legítimo para filtrar datos en lugar de su canal principal de mando y control. Los servicios web populares que actúan como mecanismo de exfiltración pueden dar una cobertura significativa debido a la probabilidad de que los hosts dentro de una red ya se estén comunicando con ellos antes del compromiso. También es posible que ya existan reglas de cortafuegos que permitan el tráfico a estos servicios.', '2025-03-13', 'f7628fb5-6ef3-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('7bb3379f-6ef4-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-13', 'Fraudulent DUO', 13364231, 'Cerrado', 'Esta alerta se dispara cuando un usuario selecciona \"Denegar\" y elige reportar el intento como fraude en lugar de un error en la Autenticación DUO.', '2025-03-14', '5aeae9f8-6ef4-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('df91b8a2-6ef4-11f0-82d3-5254007e02a0', 'P5 - Sin incidencia', '2025-03-18', 'User add Security Group', 13388807, 'Cerrado', 'La cuenta de usuario benaviad1 se ha añadido al grupo de seguridad de Windows COBAQ_Wireless, GroupName: null. Esto podría indicar que un usuario está intentando escalar sus privilegios.', '2025-03-19', 'a03ac474-6ef4-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres.'),
('4ad84629-6ef5-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-21', 'Cyber Threat', 13408694, 'Cerrado', 'El equipo de Inteligencia sobre amenazas cibernéticas detectó que se estaban compartiendo datos confidenciales en la web oscura para las siguientes cuentas.', '2025-03-22', '7a161ab4-6ef4-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('b80483b3-6ef5-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-20', 'Cyber Threat', 13402084, 'Cerrado', 'El equipo de Inteligencia sobre amenazas cibernéticas detectó que se estaban compartiendo datos confidenciales en la web oscura para las siguientes cuentas.', '2025-03-22', '858724c9-6ef5-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('55dff45c-6ef6-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-21', 'DNS File Transfer', 13402487, 'Cerrado', 'sta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de exfiltración de datos. Los adversarios pueden utilizar un servicio web externo legítimo ya existente para exfiltrar datos en lugar de su canal principal de comando y control. Los servicios web populares que actúan como mecanismo de exfiltración pueden proporcionar una cobertura significativa debido a la probabilidad de que los hosts de una red ya se estén comunicando con ellos antes de ser comprometidos. Es posible que ya existan reglas de firewall que permitan el tráfico a estos servicios.', '2025-03-21', '19752305-6ef6-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres'),
('c9184b16-6ef6-11f0-82d3-5254007e02a0', 'P3 - Media', '2025-03-20', 'DNS File Transfer', 13402339, 'Cerrado', 'Esta regla detecta transferencias de archivos a través de DNS que podrían indicar intentos de exfiltración de datos. Los adversarios pueden utilizar un servicio web externo legítimo ya existente para exfiltrar datos en lugar de su canal principal de comando y control. Los servicios web populares que actúan como mecanismo de exfiltración pueden proporcionar una cobertura significativa debido a la probabilidad de que los hosts de una red ya se estén comunicando con ellos antes de ser comprometidos. Es posible que ya existan reglas de firewall que permitan el tráfico a estos servicios.', '2025-03-21', '8f0f1c84-6ef6-11f0-82d3-5254007e02a0', 'IT', 'Resuelto por Valentina Torres');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `ID_role` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`ID_role`, `name`) VALUES
(1, 'Administrativo'),
(2, 'Administrador '),
(3, 'Super administrador');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UUID_users` varchar(36) NOT NULL DEFAULT uuid(),
  `username` varchar(8) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ID_role` int(5) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UUID_users`, `username`, `password`, `email`, `status`, `created_at`, `ID_role`, `reset_token`, `reset_expiration`) VALUES
('27779074-6e13-11f0-82d3-5254007e02a0', 'jmend683', 'F0und3v3rC0l24/*-', 'mendezjeanpaul74@gmail.com', 'Activo', '2025-07-31 13:34:59', 2, '606e3f02a3e872da13271f849882799b6e78aae3067b909b098bf2aaa5eb7c1c', '2025-08-01 09:08:51'),
('4584e3de-5d58-11f0-82d3-5254007e02a0', 'vtorr082', '12', 'vta9351394@gmail.com', 'Activo', '2025-07-10 06:36:55', 3, 'a708a149597eb7530c356f9468a917baf50923f2ac62c12b35ccefba4ef79bd7', '2025-07-21 22:20:44'),
('98fc71c1-6e19-11f0-82d3-5254007e02a0', 'amart118', 'F0und3v3rC0l0mb1a/*-', 'martinezguettea@gmail.com', 'Activo', '2025-07-31 14:21:06', 2, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`UUID_attempts`),
  ADD UNIQUE KEY `UUID_attempts_UNIQUE` (`UUID_attempts`),
  ADD KEY `UUID_users_idx` (`UUID_users`);

--
-- Indexes for table `codes_2fa`
--
ALTER TABLE `codes_2fa`
  ADD PRIMARY KEY (`UUID_2FA`),
  ADD UNIQUE KEY `UUID_2FA_UNIQUE` (`UUID_2FA`),
  ADD UNIQUE KEY `UUID_users_UNIQUE` (`UUID_users`),
  ADD KEY `users_UUID_idx` (`UUID_users`);

--
-- Indexes for table `editor_docs`
--
ALTER TABLE `editor_docs`
  ADD PRIMARY KEY (`UUID_doc`),
  ADD KEY `UUID_users` (`UUID_users`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`UUID_employees`),
  ADD KEY `fk_employees_users` (`UUID_users`);

--
-- Indexes for table `employees_report`
--
ALTER TABLE `employees_report`
  ADD PRIMARY KEY (`UUID_report`),
  ADD KEY `fk_employees_report_employees` (`UUID_employees`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ID_role`),
  ADD UNIQUE KEY `UUID_role_UNIQUE` (`ID_role`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UUID_users`),
  ADD UNIQUE KEY `UUID_users_UNIQUE` (`UUID_users`),
  ADD KEY `fk_users_role` (`ID_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `ID_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `fk_users_UUID` FOREIGN KEY (`UUID_users`) REFERENCES `users` (`UUID_users`);

--
-- Constraints for table `codes_2fa`
--
ALTER TABLE `codes_2fa`
  ADD CONSTRAINT `users_codes_UUID` FOREIGN KEY (`UUID_users`) REFERENCES `users` (`UUID_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `editor_docs`
--
ALTER TABLE `editor_docs`
  ADD CONSTRAINT `editor_docs_ibfk_1` FOREIGN KEY (`UUID_users`) REFERENCES `users` (`UUID_users`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_users` FOREIGN KEY (`UUID_users`) REFERENCES `users` (`UUID_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees_report`
--
ALTER TABLE `employees_report`
  ADD CONSTRAINT `fk_employees_report_employees` FOREIGN KEY (`UUID_employees`) REFERENCES `employees` (`UUID_employees`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`ID_role`) REFERENCES `role` (`ID_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
