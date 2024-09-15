<?php
session_start();

 if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Chef') {
     header('Location: /public/error/403.html');
     exit();
 }

include '../../src/db/db_connect.php'; // Ajusta la ruta según tu estructura de directorios

// Obtener el nombre del empleado usando el employee_id de la sesión
$employeeId = $_SESSION['employee_id'];

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Consulta para obtener el nombre del empleado
$query = "SELECT nombre FROM empleado WHERE idEmpleado = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $employeeId);

if (!$stmt->execute()) {
    die('Error en la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$nombreEmpleado = htmlspecialchars($employee['nombre']);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Sistema de cocina y productos</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/mozo/mozo.css">
</head>

<body>
    <div class="header">
        <img src="/public/assets/img/logo-removebg-preview2.png" alt="Café Sabrosos Logo" class="logo">
        <span class="site-name">Café Sabrosos</span>
    </div>
    <div class="welcome-message">
        <?php echo "¡Buenos días, " . $nombreEmpleado . '!'; ?>
    </div>
    <div class="container">
        <a href="cocina.php" class="button">
            <img class="icon" src="/public/assets/img/chef.svg" alt="Icono por defecto">
            Cocina
        </a>.
        <a href="productos.php" class="button">
            <img class="icon" src="/public/assets/img/food-menu-3-svgrepo-com.svg" alt="Icono por defecto">
            Productos
        </a>
        <a href="/src/db/logout.php" class="logout-button">
            Cerrar Sesión
        </a>
    </div>
</body>

</html>