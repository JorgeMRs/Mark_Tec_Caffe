<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';

// Obtener el ID del pedido de la URL
$idPedido = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($idPedido <= 0) {
    die('ID de pedido inválido.');
}

if (isset($_POST['cancelar_pedido'])) {
    $conn = getDbConnection();
    if (!$conn) {
        die('Error de conexión a la base de datos: ' . $conn->connect_error);
    }
    
    $cancelQuery = "UPDATE pedido SET estado = 'Cancelado' WHERE idPedido = ? AND estado = 'Pendiente'";
    $stmtCancel = $conn->prepare($cancelQuery);
    $stmtCancel->bind_param("i", $idPedido);
    
    if ($stmtCancel->execute()) {
        echo "<script>alert('El pedido ha sido cancelado.'); window.location.href = 'pedidos.php';</script>";
    } else {
        echo "<script>alert('Error al cancelar el pedido.');</script>";
    }
    
    $stmtCancel->close();
    $conn->close();
}

// Obtener los detalles del pedido
$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$query = "SELECT p.idPedido, p.tipoPedido, p.total, p.horaRecogida, p.notas, p.metodoPago, p.estado, s.nombre AS sucursal, m.numero AS numeroMesa
          FROM pedido p
          JOIN sucursal s ON p.idSucursal = s.idSucursal
          LEFT JOIN mesa m ON p.idMesa = m.idMesa
          WHERE p.idPedido = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idPedido);

if (!$stmt->execute()) {
    die('Error en la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
$pedido = $result->fetch_assoc();
$stmt->close();

if (!$pedido) {
    die('No se encontró el pedido.');
}

// Obtener los detalles del pedido
$queryDetalles = "SELECT pd.idProducto, pr.nombre AS producto, pd.cantidad, pd.precio
                  FROM pedidodetalle pd
                  JOIN producto pr ON pd.idProducto = pr.idProducto
                  WHERE pd.idPedido = ?";
$stmtDetalles = $conn->prepare($queryDetalles);
$stmtDetalles->bind_param("i", $idPedido);

if (!$stmtDetalles->execute()) {
    die('Error en la consulta de detalles: ' . $stmtDetalles->error);
}

$detalles = $stmtDetalles->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtDetalles->close();
$conn->close();

// Función para obtener el color del estado
function getEstadoColor($estado) {
    switch ($estado) {
        case 'Pendiente':
            return '#f0ad4e'; 
        case 'En Preparación':
            return '#5bc0de'; 
        case 'Listo para Recoger':
            return '#5cb85c'; 
        case 'Completado':
            return '#d9534f'; 
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
    <title>Café Sabrosos - Detalle del Pedido</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/mozo/verDetallesPedidos.css">
</head>
<body>
    <div class="container">
        <a href="pedidos.php" class="back-button">Volver a Pedidos</a>
        <h1>Detalle del Pedido</h1>
        <table>
            <tr>
                <th>ID Pedido</th>
                <td><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
            </tr>
            <tr>
                <th>Número de Mesa</th>
                <td><?php echo htmlspecialchars($pedido['numeroMesa']); ?></td>
            </tr>
            <tr>
                <th>Tipo de Pedido</th>
                <td><?php echo htmlspecialchars($pedido['tipoPedido']); ?></td>
            </tr>
            <tr>
                <th>Total</th>
                <td><?php echo htmlspecialchars(number_format($pedido['total'], 2, ',', '.')); ?> €</td>
            </tr>
            <tr>
                <th>Hora de Recogida</th>
                <td><?php echo htmlspecialchars($pedido['horaRecogida']); ?></td>
            </tr>
            <tr>
                <th>Sucursal</th>
                <td><?php echo htmlspecialchars($pedido['sucursal']); ?></td>
            </tr>
            <tr>
                <th>Notas</th>
                <td><?php echo htmlspecialchars($pedido['notas']); ?></td>
            </tr>
            <tr>
                <th>Método de Pago</th>
                <td><?php echo htmlspecialchars($pedido['metodoPago']); ?></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td class="status" style="background-color: <?php echo getEstadoColor($pedido['estado']); ?>;">
                    <?php echo htmlspecialchars($pedido['estado']); ?>
                </td>
            </tr>
        </table>
        <?php if ($pedido['estado'] === 'Pendiente'): ?>
        <form method="POST">
            <button type="submit" name="cancelar_pedido" class="cancel-button">Cancelar Pedido</button>
        </form>
        <?php endif; ?>
        <h2>Detalles del Pedido</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detalle['producto']); ?></td>
                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($detalle['precio'], 2, ',', '.')); ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
