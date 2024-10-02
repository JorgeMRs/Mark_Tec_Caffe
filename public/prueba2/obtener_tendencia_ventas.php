<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener la tendencia de ventas mensuales
$queryTendenciaVentas = "
    SELECT 
        MONTHNAME(fechaPedido) AS mes, 
        SUM(total) AS ventas
    FROM 
        pedido
    WHERE 
        YEAR(fechaPedido) = YEAR(CURDATE())
    GROUP BY 
        MONTH(fechaPedido)
    ORDER BY 
        MONTH(fechaPedido)
";

$resultTendenciaVentas = $conn->query($queryTendenciaVentas);

$tendenciaVentas = [];
while ($row = $resultTendenciaVentas->fetch_assoc()) {
    $tendenciaVentas[] = $row;
}

echo json_encode($tendenciaVentas);
$conn->close();
?>