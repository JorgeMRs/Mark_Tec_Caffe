<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';


$limit = 10; // Número de pedidos por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Obtener la sucursal del empleado
$employee_id = $_SESSION['employee_id'];
$querySucursal = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
$stmtSucursal = $conn->prepare($querySucursal);
$stmtSucursal->bind_param('i', $employee_id);

if (!$stmtSucursal->execute()) {
    die('Error en la consulta de sucursal: ' . $stmtSucursal->error);
}

$sucursalResult = $stmtSucursal->get_result()->fetch_assoc();
$sucursal_id = $sucursalResult['idSucursal'];

$stmtSucursal->close();

// Definir qué estados consideramos como 'activos'
$activos = ['Pendiente', 'En Preparación', 'Listo para Recoger'];
$placeholders = implode(',', array_fill(0, count($activos), '?'));


$query = "SELECT p.idPedido, p.tipoPedido, p.total, p.horaRecogida, s.nombre AS sucursal, m.numero AS numeroMesa, p.estado
          FROM pedido p
          LEFT JOIN sucursal s ON p.idSucursal = s.idSucursal
          LEFT JOIN mesa m ON p.idMesa = m.idMesa
          WHERE p.estado IN ($placeholders) AND p.idSucursal = ?
          ORDER BY p.idPedido ASC
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$params = array_merge($activos, [$sucursal_id, $limit, $offset]);
$stmt->bind_param(str_repeat('s', count($activos)) . 'iii', ...$params);

if (!$stmt->execute()) {
    die('Error en la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);

// Consulta para obtener el número total de pedidos activos
$queryCount = "SELECT COUNT(*) AS total
               FROM pedido p
               WHERE p.estado IN ($placeholders) AND p.idSucursal = ?";

$stmtCount = $conn->prepare($queryCount);
$stmtCount->bind_param(str_repeat('s', count($activos)) . 'i', ...array_merge($activos, [$sucursal_id]));

if (!$stmtCount->execute()) {
    die('Error en la consulta de conteo: ' . $stmtCount->error);
}

$countResult = $stmtCount->get_result()->fetch_assoc();
$totalPedidos = $countResult['total'];
$totalPages = ceil($totalPedidos / $limit);

$stmt->close();
$stmtCount->close();
$conn->close();

function getEstadoColor($estado) {
    switch ($estado) {
        case 'Pendiente':
            return '#FFF3CD';
        case 'En Preparación':
            return '#61c0bf'; 
        case 'Listo para Recoger':
            return '#D5EDDB'; 
        case 'Completado':
            return '#F9D7DB'; 
        case 'Cancelado':
            return '#777'; 
        default:
            return '#fff';
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Pedidos Activos</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/mozo/mozoPedidos.css">
</head>
<body>
    <div class="container">
    <div class="header-buttons">
            <a href="mozo.php" class="back-button" onclick="window.history.back();">Volver a la Página Principal</a>
            <a href="crearPedido.php" class="new-order-button">Crear Nuevo Pedido</a>
        </div>
        <h1>Pedidos Activos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Estado</th>
                    <th>Tipo de Pedido</th>
                    <th>Total</th>
                    <th>Hora de Recogida</th>
                    <th>Sucursal</th>
                    <th>Número de Mesa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
                        <td style="background-color: <?php echo getEstadoColor($pedido['estado']); ?>;">
                            <?php echo htmlspecialchars($pedido['estado']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($pedido['tipoPedido']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($pedido['total'], 2, ',', '.')); ?> €</td>
                        <td><?php echo htmlspecialchars($pedido['horaRecogida']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['sucursal']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['numeroMesa']) ? htmlspecialchars($pedido['numeroMesa']) : 'N/A'; ?></td> <!-- Mostrar número de mesa o 'N/A' -->
                        <td>
                            <a href="verDetallePedido.php?id=<?php echo htmlspecialchars($pedido['idPedido']); ?>" class="button">Ver Detalles</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">« Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente »</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>