<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();

if ($response['role'] !== 'employee' || $response['rol'] !== 'Mozo') {
    header('Location: /public/error/403.html'); // Redirigir a la página de inicio de sesión
    exit();
}

$employee_id = $response['idEmpleado']; 

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Consulta para obtener el nombre del empleado
$query = "SELECT nombre FROM empleado WHERE idEmpleado = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $employee_id);

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
    <title>Café Sabrosos - Sistema de pedidos y reservas</title>
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
        <a href="pedidos.php" class="button">
            <img class="icon" src="/public/assets/img/pedidos.svg" alt="Icono por defecto">
            Pedidos
        </a>
        <a href="crearReserva.php" class="button">
            <img class="icon" src="/public/assets/img/reservas.svg" alt="Icono por defecto">
            Reservas
        </a>
        <a href="/src/auth/logout.php" class="logout-button">
            Cerrar Sesión
        </a>
    </div>
</body>

</html>