<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Obtener idSucursal del empleado
$idEmpleado = $_SESSION['employee_id'];
$querySucursal = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
$stmtSucursal = $conn->prepare($querySucursal);
$stmtSucursal->bind_param('i', $idEmpleado);
$stmtSucursal->execute();
$resultSucursal = $stmtSucursal->get_result();

if ($resultSucursal->num_rows === 0) {
    die('Sucursal no encontrada para el empleado.');
}

$sucursalRow = $resultSucursal->fetch_assoc();
$idSucursal = $sucursalRow['idSucursal'];

$stmtSucursal->close();

// Obtener el término de búsqueda, si existe
$searchTerm = isset($_POST['buscarProducto']) ? '%' . $conn->real_escape_string($_POST['buscarProducto']) . '%' : '%';

// Obtener productos disponibles
$queryProductos = "
    SELECT p.idProducto, p.nombre, p.precio, p.stock, c.nombre AS categoria 
    FROM producto p
    JOIN categoria c ON p.idCategoria = c.idCategoria
    WHERE p.stock > 0 AND p.nombre LIKE ?
    ORDER BY c.nombre, p.nombre";

$stmt = $conn->prepare($queryProductos);
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$resultProductos = $stmt->get_result();

$productosPorCategoria = [];
if ($resultProductos && $resultProductos->num_rows > 0) {
    while ($row = $resultProductos->fetch_assoc()) {
        $productosPorCategoria[$row['categoria']][] = $row;
    }
}

// Obtener mesas disponibles para la sucursal del empleado
$queryMesas = "SELECT idMesa, numero FROM mesa WHERE idSucursal = ?";
$stmtMesas = $conn->prepare($queryMesas);
$stmtMesas->bind_param('i', $idSucursal);
$stmtMesas->execute();
$resultMesas = $stmtMesas->get_result();

$mesas = [];
if ($resultMesas && $resultMesas->num_rows > 0) {
    while ($row = $resultMesas->fetch_assoc()) {
        $mesas[] = $row;
    }
}

$stmt->close(); 
$stmtMesas->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Crear Nuevo Pedido</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/mozo/crearPedido.css">
</head>

<body>
    <div class="container">
        <div class="header-buttons">
            <a class="button" onclick="window.history.back();">Volver</a>
            <a href="/public/tienda.php" class="button">Ver productos</a>
        </div>
        <h1>Crear Nuevo Pedido</h1>
        <form id="crearPedidoForm">
            <label for="buscarProducto">Buscar Producto</label>
            <input type="text" id="buscarProducto" name="buscarProducto" placeholder="Buscar por nombre de producto" onkeyup="filterProducts()">

            <label for="productos">Seleccionar Productos</label>
            <div id="productosContainer">
                <?php foreach ($productosPorCategoria as $categoria => $productos): ?>
                    <h3><?php echo htmlspecialchars($categoria); ?></h3>
                    <?php foreach ($productos as $producto): ?>
                        <div class="product-row" data-producto="<?php echo htmlspecialchars($producto['nombre']); ?>" data-precio="<?php echo htmlspecialchars($producto['precio']); ?>">
                            <input type="checkbox" name="productos[<?php echo $producto['idProducto']; ?>]" value="<?php echo $producto['idProducto']; ?>">
                            <label><?php echo htmlspecialchars($producto['nombre']) . ' - ' . number_format($producto['precio'], 2, ',', '.') . ' €'; ?></label>
                            <div class="product-quantity">
                                <button type="button" class="decrement" data-product-id="<?php echo $producto['idProducto']; ?>">-</button>
                                <span id="cantidad-<?php echo $producto['idProducto']; ?>">0</span>
                                <button type="button" class="increment" data-product-id="<?php echo $producto['idProducto']; ?>">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div id="productosSeleccionadosContainer" style="margin-top: 20px;">
                No hay productos seleccionados.
            </div>
            <label for="tipoPedido">Tipo de Pedido</label>
            <select name="tipoPedido" id="tipoPedido" required onchange="toggleHoraRecogida()">
                <option value="">Seleccione un tipo de pedido</option>
                <option value="Para Llevar">Para Llevar</option>
                <option value="En el local">En el local</option>
            </select>

            <div id="horaRecogidaContainer">
                <label for="horaRecogida">Hora de Recogida (solo si es para llevar)</label>
                <input type="text" id="horaRecogida" name="horaRecogida" placeholder="Formato: HH:MM">
            </div>

            <label for="numeroMesa">Número de Mesa (solo para comer en el local)</label>
            <select id="numeroMesa" name="numeroMesa" style="display: none;">
                <option value="">Seleccione una mesa</option>
                <?php foreach ($mesas as $mesa): ?>
                    <option value="<?php echo $mesa['idMesa']; ?>"><?php echo 'Mesa ' . $mesa['numero']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="notas">Notas del Pedido</label>
            <textarea id="notas" name="notas" placeholder="Notas adicionales para el pedido"></textarea>

            <button type="submit" class="button">Crear Pedido</button>
        </form>
    </div>
</body>
<script src="/public/assets/js/mozo/mozo.js"></script>
</html>