<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener las ventas diarias de la semana actual
$queryVentasSemanales = "
    SELECT 
        DAYNAME(fechaPedido) AS dia, 
        SUM(total) AS ventas
    FROM 
        pedido
    WHERE 
        YEARWEEK(fechaPedido, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY 
        DAYOFWEEK(fechaPedido)
    ORDER BY 
        DAYOFWEEK(fechaPedido)
";

$resultVentasSemanales = $conn->query($queryVentasSemanales);

$ventasSemanales = [];
while ($row = $resultVentasSemanales->fetch_assoc()) {
    $ventasSemanales[] = $row;
}

echo json_encode($ventasSemanales);
$conn->close();
?>