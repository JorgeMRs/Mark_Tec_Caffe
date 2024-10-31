<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener la distribución de ventas por categoría
$queryDistribucionVentas = "
    SELECT 
        categoria.nombre AS categoria, 
        SUM(pedidodetalle.cantidad * pedidodetalle.precio) AS ventas
    FROM 
        pedidodetalle
    JOIN 
        producto ON pedidodetalle.idProducto = producto.idProducto
    JOIN 
        categoria ON producto.idCategoria = categoria.idCategoria
    GROUP BY 
        categoria.nombre
";

$resultDistribucionVentas = $conn->query($queryDistribucionVentas);

$distribucionVentas = [];
while ($row = $resultDistribucionVentas->fetch_assoc()) {
    $distribucionVentas[] = $row;
}

echo json_encode($distribucionVentas);
$conn->close();
?>