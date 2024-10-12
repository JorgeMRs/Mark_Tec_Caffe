<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../src/db/db_connect.php'; 

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

try {
    // Obtener el ID de la sucursal desde la URL
    $sucursalId = isset($_GET['sucursal']) ? intval($_GET['sucursal']) : 0;

    if ($sucursalId <= 0) {
        throw new Exception('ID de sucursal no válido.');
    }

    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Consultar mesas para la sucursal seleccionada
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
    // Manejo de errores
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Mesas';

$customCSS = [
    '/public/assets/css/mesas.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'

];

$customJS = [
  '/public/assets/js/languageSelect.js',
  '/public/assets/js/updateCartCounter.js'
];

include 'templates/head.php' ?>


<?php include 'templates/nav.php' ?>

<body>
    <div id="reservaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reserva de Mesa</h2>
            <form id="reservaForm" action="/src/reservas/reservar.php" method="post">
                <input type="hidden" id="mesaId" name="mesa_id">
                <input type="hidden" id="sucursalId" name="sucursal_id">
                <body data-sucursal-id="<?php echo $sucursalId; ?>">
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
                <div class="mesa <?php echo $class; ?>">
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
        </div>
    </div>
    <?php include 'templates/footer.php' ?>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/mesas.js"></script>
</html>