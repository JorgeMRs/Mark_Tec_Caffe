<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';

$mesaId = isset($_GET['mesaId']) ? intval($_GET['mesaId']) : 0;

$response = ['success' => false];

try {
    $conn = getDbConnection();

    // Query to get reservation details linked to the mesa
    $sql = "SELECT r.idReserva, r.fechaReserva, r.estado, r.cantidadPersonas, 
                   e.nombre, e.apellido 
            FROM reserva r 
            LEFT JOIN empleado e ON r.idEmpleado = e.idEmpleado 
            WHERE r.idMesa = ? AND r.estado IN ('reservado', 'ocupado')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mesaId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['reserva'] = $result->fetch_assoc();
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
