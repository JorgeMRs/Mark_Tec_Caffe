<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener las ventas del día
$queryVentas = "SELECT SUM(total) AS ventasDelDia FROM pedido WHERE DATE(fechaPedido) = CURDATE()";
$resultVentas = $conn->query($queryVentas);
$ventasDelDia = $resultVentas->fetch_assoc()['ventasDelDia'] ?? 0;

// Consulta para obtener los pedidos activos
$queryPedidos = "SELECT COUNT(*) AS pedidosActivos FROM pedido WHERE estado = 'Pendiente'";
$resultPedidos = $conn->query($queryPedidos);
$pedidosActivos = $resultPedidos->fetch_assoc()['pedidosActivos'] ?? 0;

// Consulta para obtener el inventario
$queryInventario = "SELECT COUNT(*) AS totalArticulos FROM producto";
$resultInventario = $conn->query($queryInventario);
$totalArticulos = $resultInventario->fetch_assoc()['totalArticulos'] ?? 0;

$resumen = [
    'ventasDelDia' => $ventasDelDia,
    'pedidosActivos' => $pedidosActivos,
    'totalArticulos' => $totalArticulos
];



echo json_encode($resumen);
$conn->close();
?>