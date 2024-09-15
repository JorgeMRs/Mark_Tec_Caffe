<?php
session_start();
require_once '../../vendor/autoload.php'; 
require_once '../../src/db/db_connect.php'; 

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../');
$dotenv->load();

try {
    // Verificar si el usuario está logueado como Mozo
    if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
        header('Location: /public/error/403.html');
        exit();
    }

    $empleadoId = intval($_SESSION['employee_id']);

    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Consultar la sucursal donde trabaja el mozo logueado
    $sqlEmpleado = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
    $stmtEmpleado = $conn->prepare($sqlEmpleado);
    if (!$stmtEmpleado) {
        throw new Exception('Error al preparar la consulta del empleado: ' . $conn->error);
    }
    $stmtEmpleado->bind_param("i", $empleadoId);
    $stmtEmpleado->execute();
    $empleado = $stmtEmpleado->get_result()->fetch_assoc();

    if (!$empleado) {
        throw new Exception('Empleado no encontrado.');
    }

    $sucursalId = $empleado['idSucursal'];

    // Consultar mesas para la sucursal del empleado
    $sqlMesas = "SELECT mesa.*, COALESCE(reserva.estado, 'disponible') AS estadoReserva
                 FROM mesa
                 LEFT JOIN reserva ON mesa.idMesa = reserva.idMesa 
                 AND reserva.fechaReserva = (SELECT MAX(fechaReserva) 
                                              FROM reserva 
                                              WHERE reserva.idMesa = mesa.idMesa 
                                              AND reserva.estado IN ('reservado', 'ocupado'))
                 WHERE mesa.idSucursal = ?";
    $stmtMesas = $conn->prepare($sqlMesas);
    if (!$stmtMesas) {
        throw new Exception('Error al preparar la consulta de mesas: ' . $conn->error);
    }
    $stmtMesas->bind_param("i", $sucursalId);
    $stmtMesas->execute();
    $mesas = $stmtMesas->get_result();

    if (!$mesas) {
        throw new Exception('Error al obtener mesas: ' . $stmtMesas->error);
    }

    // Consultar información de la sucursal
    $sqlSucursal = "SELECT * FROM sucursal WHERE idSucursal = ?";
    $stmtSucursal = $conn->prepare($sqlSucursal);
    if (!$stmtSucursal) {
        throw new Exception('Error al preparar la consulta de sucursal: ' . $conn->error);
    }
    $stmtSucursal->bind_param("i", $sucursalId);
    $stmtSucursal->execute();
    $sucursal = $stmtSucursal->get_result()->fetch_assoc();

    if (!$sucursal) {
        throw new Exception('Sucursal no encontrada.');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Café Sabrosos - Sistema de reservas</title>
    <link rel="stylesheet" href="../assets/css/mozo/crearReserva.css">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
</head>
<body data-sucursal-id="<?php echo htmlspecialchars($sucursalId); ?>">
<div class="header">
        <img src="/public/assets/img/logo-removebg-preview2.png" alt="Café Sabrosos Logo" class="logo">
        <span class="site-name">Café Sabrosos</span>
    </div>
    <div class="back-button-container">
    <button onclick="window.history.back()"; class="btn-volver">Volver</button>
</div>
<div id="reservaModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reserva de Mesa</h2>
        <form id="reservaForm" action="/src/reservas/reservar.php" method="post">
            <input type="hidden" id="mesaId" name="mesa_id">
            <input type="hidden" id="sucursalId" name="sucursal_id" value="<?php echo htmlspecialchars($sucursalId); ?>">
            <label for="fechaReserva">Fecha:</label>
            <input type="date" id="fechaReserva" name="fechaReserva" required>

            <label for="horaReserva">Hora:</label>
            <input type="time" id="horaReserva" name="horaReserva">

            <label for="cantidadPersonas">Cantidad de Personas:</label>
            <select id="cantidadPersonas" name="cantidadPersonas" required>
                <!-- Opciones se llenarán con JavaScript -->
            </select>
            <div id="errorReserva" class="error-message"></div>
            <input type="submit" value="Reservar" class="btn-reservar2">
        </form>
    </div>
</div>

<div id="avisoModal" class="modal">
    <div class="modal-content">
        <span class="close aviso-close">&times;</span>
        <h2>Acceso Requerido</h2>
        <p>Para realizar una reserva, debes iniciar sesión. Serás redirigido a la página de inicio de sesión.</p>
    </div>
</div>

<div class="container">
    <h1>Mesas - Sucursal <?php echo htmlspecialchars($sucursal['nombre']); ?></h1>
    <div class="mesas">
        <?php if ($mesas->num_rows > 0): ?>
            <?php while ($row = $mesas->fetch_assoc()): ?>
                <?php
                $class = 'disponible';
                $mensaje = '';
                if ($row['estadoReserva'] == 'ocupado') {
                    $class = 'ocupada';
                    $mensaje = 'Mesa ocupada';
                } elseif ($row['estadoReserva'] == 'reservado') {
                    $class = 'reservado';
                    $mensaje = 'Mesa reservada';
                }
                ?>
                <div class="mesa <?php echo htmlspecialchars($class); ?>">
                    <h3>Mesa <?php echo htmlspecialchars($row['numero']); ?></h3>
                    <p>Capacidad: <?php echo htmlspecialchars($row['capacidad']); ?></p>
                    <?php if ($class == 'disponible'): ?>
                        <a href="javascript:void(0);" class="btn-reservar"
                            data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>"
                            data-capacidad="<?php echo htmlspecialchars($row['capacidad']); ?>">Reservar</a>
                    <?php else: ?>
                        <div class="mensaje-no-disponible"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay mesas disponibles en esta sucursal.</p>
        <?php endif; ?>
    </div>
</div>

<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/mesas.js"></script>
</body>
</html>
