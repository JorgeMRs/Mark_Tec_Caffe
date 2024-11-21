<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener la retroalimentación
$query = "
    SELECT 
        r.idRetroalimentacion AS id,
        r.idCliente AS idCliente,
        c.nombre AS clienteNombre,
        c.apellido AS clienteApellido,
        r.nivelSatisfaccion AS nivelSatisfaccion,
        r.comentario AS comentario
    FROM 
        retroalimentacion r
    JOIN 
        cliente c ON r.idCliente = c.idCliente
    ORDER BY 
        r.idRetroalimentacion ASC
";

$result = $conn->query($query);

$retroalimentacion = [];
while ($row = $result->fetch_assoc()) {
    $retroalimentacion[] = $row;
}

echo json_encode($retroalimentacion);
$conn->close();
?>