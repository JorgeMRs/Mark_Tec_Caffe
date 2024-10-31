<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener los productos más vendidos
$queryProductosMasVendidos = "
    SELECT 
        producto.nombre AS producto, 
        SUM(pedidodetalle.cantidad) AS cantidad
    FROM 
        pedidodetalle
    JOIN 
        producto ON pedidodetalle.idProducto = producto.idProducto
    GROUP BY 
        producto.nombre
    ORDER BY 
        cantidad DESC
    LIMIT 6
";

$resultProductosMasVendidos = $conn->query($queryProductosMasVendidos);

$productosMasVendidos = [];
while ($row = $resultProductosMasVendidos->fetch_assoc()) {
    $productosMasVendidos[] = $row;
}

echo json_encode($productosMasVendidos);
$conn->close();
?>