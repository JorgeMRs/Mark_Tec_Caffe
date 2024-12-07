<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();

$employee_id = $response['idEmpleado'];
$role = $response['rol'];

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../');
$dotenv->load();

try {

    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Consultar la sucursal donde trabaja el mozo logueado
    $sqlEmpleado = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
    $stmtEmpleado = $conn->prepare($sqlEmpleado);
    if (!$stmtEmpleado) {
        throw new Exception('Error al preparar la consulta del empleado: ' . $conn->error);
    }
    $stmtEmpleado->bind_param("i", $employee_id);
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

    function getMesaEstadoColor($estado)
    {
        switch ($estado) {
            case 'reservado':
                return '#FFF3CD'; // Light yellow for reserved
            case 'disponible':
                return '#D5EDDB'; // Light green for available
            case 'cancelado':
                return '#F9D7DB'; // Light red for cancelled
            case 'ocupado':
                return '#61c0bf'; // Light blue for occupied
            case 'finalizado':
                return '#777'; // Gray for finished
            default:
                return '#fff'; // Default white color
        }
    }

    // Consultar reservas actuales de la sucursal
    $sqlReservas = "SELECT r.idReserva AS idReserva, r.fechaReserva, r.estado, r.cantidadPersonas, 
                       m.numero AS numeroMesa, e.nombre AS nombreEmpleado, e.apellido AS apellidoEmpleado
                FROM reserva r
                JOIN mesa m ON r.idMesa = m.idMesa
                LEFT JOIN empleado e ON r.idEmpleado = e.idEmpleado
                WHERE m.idSucursal = ? AND r.estado IN ('reservado', 'ocupado')";
    $stmtReservas = $conn->prepare($sqlReservas);
    if (!$stmtReservas) {
        throw new Exception('Error al preparar la consulta de reservas: ' . $conn->error);
    }
    $stmtReservas->bind_param("i", $sucursalId);
    $stmtReservas->execute();
    $reservas = $stmtReservas->get_result();
    $reservasArray = [];
    while ($reserva = $reservas->fetch_assoc()) {
        $reservasArray[$reserva['numeroMesa']] = $reserva; // Usar el número de mesa como clave
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<?php

$pageTitle = 'Café Sabrosos - Sistema de Reservas';

$customCSS = [
    '/public/assets/css/mozo/crearReserva.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];

include '../templates/head.php' ?>

<body data-sucursal-id="<?php echo htmlspecialchars($sucursalId); ?>">
    <div class="header">
        <img src="/public/assets/img/logo-removebg-preview2.png" alt="Café Sabrosos Logo" class="logo">
        <span class="site-name">Café Sabrosos</span>
    </div>
    <div class="back-button-container">
        <button onclick="window.history.back()" ; class="btn-volver">Volver</button>
    </div>
    <div id="reservaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="reservaTitle">Reserva de Mesa</h2>
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
    <!-- Modal de confirmación -->
    <div id="modalConfirmacion" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirmar Finalización</h2>
            <p>¿Está seguro de que desea finalizar la reserva?</p>
            <button id="confirmarFinalizar" class="btn-confirmar">Sí, finalizar</button>
            <button id="cancelarFinalizar" class="btn-cancelar">Cancelar</button>
        </div>
    </div>


    <div id="cancelModal" class="modal-cancelacion">
        <div class="modal-content-cancelacion">
            <span class="close-cancelacion" id="closeModal">&times;</span>
            <h2 class="confirmationTitle">Cancelar Reserva</h2>
            <p>¿Estás seguro de que deseas cancelar esta reserva?</p>
            <label for="notes" class="label-notas">Notas (obligatorias):</label>
            <textarea id="notes" class="input-notas" rows="4" required></textarea>
            <button id="confirmCancel" class="button-confirmar">Confirmar Cancelación</button>
        </div>
    </div>

    <div id="confirmationModal" class="modal-confirmacion" style="display: none;">
    <div class="modal-content-confirmacion">
        <span class="close-confirmacion" id="closeConfirmationModal">&times;</span>
        <h2">Cancelación Exitosa</h2>
        <p>La reserva ha sido cancelada correctamente.</p>
        <button id="okButton" class="button-ok">OK</button>
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
                    $showDetailsButton = false; // Control visibility of details button
                    $showFinalizeButton = false; // Control visibility of finalize button
                    $showChangeStatusButton = false; // Control visibility of change status button
                    $showCancelButton = false; // Control visibility of cancel button

                    // Verificar si hay una reserva para esta mesa
                    if (isset($reservasArray[$row['numero']])) {
                        $reserva = $reservasArray[$row['numero']];
                        if ($reserva['estado'] == 'ocupado') {
                            $class = 'ocupada';
                            $mensaje = 'Mesa ocupada';
                            $showDetailsButton = true; // Show details button if occupied
                            $showFinalizeButton = true; // Show finalize button if occupied
                        } elseif ($reserva['estado'] == 'reservado') {
                            $class = 'reservado';
                            $mensaje = 'Mesa reservada';
                            $showChangeStatusButton = true; // Show change status button if reserved
                            $showDetailsButton = true; // Show details button if reserved
                            $showCancelButton = true; // Show cancel button if reserved
                        }
                    } else {
                        // Si no hay reserva, la mesa está disponible
                        $class = 'disponible';
                        $mensaje = 'Mesa disponible';
                    }
                    ?>
                    <div class="mesa <?php echo htmlspecialchars($class); ?>">
                        <h3>Mesa <?php echo htmlspecialchars($row['numero']); ?></h3>
                        <p>Capacidad: <?php echo htmlspecialchars($row['capacidad']); ?></p>

                        <?php if ($class == 'disponible'): ?>
                            <a href="javascript:void(0);" class="btn-reservar"
                                data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>"
                                data-capacidad="<?php echo htmlspecialchars($row['capacidad']); ?>">Reservar</a>
                            <div class="mensaje-disponible">Mesa disponible</div>
                        <?php else: ?>
                            <div class="mensaje-no-disponible"><?php echo htmlspecialchars($mensaje); ?></div>
                        <?php endif; ?>

                        <?php if ($showChangeStatusButton): ?>
                            <a href="javascript:void(0);" class="btn-cambiar-estado"
                                data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>">
                                Ocupado
                            </a>
                        <?php endif; ?>

                        <?php if ($showDetailsButton): ?>
                            <button class="btn-detalles"
                                data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>"
                                onclick="fetchReservationDetails(<?php echo htmlspecialchars($row['idMesa']); ?>)">Obtener Detalles</button>
                        <?php endif; ?>

                        <?php if ($showFinalizeButton): ?>
                            <a href="javascript:void(0);" class="btn-finalizar-reserva"
                                data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>" style="display: block;">
                                Finalizar reserva
                            </a>
                        <?php endif; ?>

                        <!-- Botón para cancelar reserva, visible solo si la mesa está reservada -->
                        <?php if ($showCancelButton): ?>
                            <a class="btn-cancelar-reserva"
                                href="javascript:void(0);"
                                data-reserva-id="<?php echo htmlspecialchars($reserva['idReserva']); ?>"
                                onclick="openCancelModal(<?php echo htmlspecialchars($reserva['idReserva']); ?>)">
                                Cancelar Reserva
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay mesas disponibles en esta sucursal.</p>
            <?php endif; ?>
        </div>
    </div>
    <div id="detallesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDetailsModal()">&times;</span>
            <h2>Detalles de la Reserva</h2>
            <div id="detallesReservaContent"></div>
        </div>
    </div>
    <script>

    </script>
    <script src="/public/assets/js/mozo/mesasMozo.js"></script>
</body>

</html>