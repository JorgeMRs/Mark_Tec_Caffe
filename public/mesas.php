<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../src/db/db_connect.php';

use Dotenv\Dotenv;
use Stichoza\GoogleTranslate\GoogleTranslate;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

// Obtener el idioma seleccionado o el idioma por defecto
$idioma = isset($_SESSION['idioma']) ? $_SESSION['idioma'] : 'es';
$tr = new GoogleTranslate($idioma);

// Verificar si hay un cambio de idioma mediante AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idioma'])) {
    $_SESSION['idioma'] = $_POST['idioma'];
    echo json_encode(['status' => 'success']);
    exit();
}

try {
    // Obtener el ID de la sucursal desde la URL
    $sucursalId = isset($_GET['sucursal']) ? intval($_GET['sucursal']) : 0;

    if ($sucursalId <= 0) {
        throw new Exception($tr->translate('ID de sucursal no válido.'));
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
        throw new Exception($tr->translate('Error al preparar la consulta de mesas: ') . $conn->error);
    }
    $stmtMesas->bind_param("i", $sucursalId);
    $stmtMesas->execute();
    $mesas = $stmtMesas->get_result();
    if (!$mesas) {
        throw new Exception($tr->translate('Error al obtener mesas: ') . $stmtMesas->error);
    }

    // Consultar información de la sucursal
    $sqlSucursal = "SELECT * FROM sucursal WHERE idSucursal = ?";
    $stmtSucursal = $conn->prepare($sqlSucursal);
    if (!$stmtSucursal) {
        throw new Exception($tr->translate('Error al preparar la consulta de sucursal: ') . $conn->error);
    }
    $stmtSucursal->bind_param("i", $sucursalId);
    $stmtSucursal->execute();
    $sucursal = $stmtSucursal->get_result()->fetch_assoc();
    if (!$sucursal) {
        throw new Exception($tr->translate('Sucursal no encontrada.'));
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<?php
$pageTitle = 'Café Sabrosos - Reservas de Mesas';

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

include 'templates/head.php';
?>

<?php include 'templates/nav.php'; ?>

<body data-sucursal-id="<?php echo htmlspecialchars($sucursalId); ?>">
    <div id="reservaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><?php echo $tr->translate('Reserva de Mesa'); ?></h2>
            <form id="reservaForm" action="/src/reservas/reservar.php" method="post">
                <input type="hidden" id="mesaId" name="mesa_id">
                <input type="hidden" id="sucursalId" name="sucursal_id">
                <label for="fechaReserva"><?php echo $tr->translate('Fecha:'); ?></label>
                <input type="date" id="fechaReserva" name="fechaReserva" required>

                <label for="horaReserva"><?php echo $tr->translate('Hora:'); ?></label>
                <input type="time" id="horaReserva" name="horaReserva">

                <label for="cantidadPersonas"><?php echo $tr->translate('Cantidad de Personas:'); ?></label>
                <select id="cantidadPersonas" name="cantidadPersonas" required>
                    <!-- Opciones se llenarán con JavaScript -->
                </select>
                <div id="errorReserva" class="error-message"></div>
                <input type="submit" value="<?php echo $tr->translate('Reservar'); ?>" class="btn-reservar2">
            </form>
        </div>
    </div>
    <div id="avisoModal" class="modal">
        <div class="modal-content">
            <span class="close aviso-close">&times;</span>
            <h2><?php echo $tr->translate('Acceso Requerido'); ?></h2>
            <p><?php echo $tr->translate('Para realizar una reserva, debes iniciar sesión. Serás redirigido a la página de inicio de sesión.'); ?></p>
        </div>
    </div>
    <div class="container">
        <h1><?php echo $tr->translate('Mesas - Sucursal') . ' ' . htmlspecialchars($sucursal['nombre']); ?></h1>
        <div class="mesas">
            <?php while ($row = $mesas->fetch_assoc()): ?>
                <?php
                $class = 'disponible';
                $mensaje = '';
                if ($row['estadoReserva'] == 'ocupado') {
                    $class = 'ocupada';
                    $mensaje = $tr->translate('Mesa ocupada');
                } elseif ($row['estadoReserva'] == 'reservado') {
                    $class = 'reservado';
                    $mensaje = $tr->translate('Mesa reservada');
                }
                ?>
                <div class="mesa <?php echo $class; ?>">
                <h3><?php echo ($idioma === 'es') ? 'Mesa ' : $tr->translate('Table') . ' '; echo htmlspecialchars($row['numero']); ?></h3>
                <p><?php echo $tr->translate('Capacidad:') . ' ' . htmlspecialchars($row['capacidad']); ?></p>
                    <?php if ($class == 'disponible'): ?>
                        <a href="javascript:void(0);" class="btn-reservar"
                            data-mesa-id="<?php echo htmlspecialchars($row['idMesa']); ?>"
                            data-capacidad="<?php echo htmlspecialchars($row['capacidad']); ?>"><?php echo $tr->translate('Reservar'); ?></a>
                    <?php else: ?>
                        <div class="mensaje-no-disponible"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include 'templates/footer.php'; ?>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/mesas.js"></script>
</html>
