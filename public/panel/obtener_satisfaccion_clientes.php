<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener la satisfacción de clientes
$querySatisfaccionClientes = "
    SELECT 
        AVG(CASE WHEN nivelSatisfaccion = 'Muy bajo' THEN 1
                 WHEN nivelSatisfaccion = 'Bajo' THEN 2
                 WHEN nivelSatisfaccion = 'Medio' THEN 3
                 WHEN nivelSatisfaccion = 'Alto' THEN 4
                 WHEN nivelSatisfaccion = 'Muy alto' THEN 5
            END) AS satisfaccion_promedio
    FROM 
        retroalimentacion
    GROUP BY 
        nivelSatisfaccion
";

$resultSatisfaccionClientes = $conn->query($querySatisfaccionClientes);

$satisfaccionClientes = [];
while ($row = $resultSatisfaccionClientes->fetch_assoc()) {
    $satisfaccionClientes[] = $row;
}

echo json_encode($satisfaccionClientes);
$conn->close();
?>