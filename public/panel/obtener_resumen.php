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
$ventasDelDia = (float)($resultVentas->fetch_assoc()['ventasDelDia'] ?? 0); // Asegúrate de convertir a float

// Consulta para obtener los pedidos activos (excluyendo Completado y Cancelado)
$queryPedidos = "SELECT COUNT(*) AS pedidosActivos FROM pedido WHERE estado NOT IN ('Completado', 'Cancelado')";
$resultPedidos = $conn->query($queryPedidos);
$pedidosActivos = (int)($resultPedidos->fetch_assoc()['pedidosActivos'] ?? 0); // Asegúrate de convertir a int

// Consulta para obtener el inventario
$queryInventario = "SELECT COUNT(*) AS totalArticulos FROM producto";
$resultInventario = $conn->query($queryInventario);
$totalArticulos = (int)($resultInventario->fetch_assoc()['totalArticulos'] ?? 0); // Asegúrate de convertir a int

$resumen = [
    'ventasDelDia' => $ventasDelDia,
    'pedidosActivos' => $pedidosActivos,
    'totalArticulos' => $totalArticulos
];

echo json_encode($resumen);
$conn->close();
?>
