<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();
$employee_id = $response['idEmpleado']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mesa_id = $_POST['mesa_id'] ?? null;

    if (!$mesa_id || !$employee_id) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para finalizar la reserva.']);
        exit;
    }

    try {
        $conn = getDbConnection();

        // Cambia el estado de la reserva a 'finalizado'
        $sql = "UPDATE reserva SET estado = 'finalizado' WHERE idMesa = ? AND estado = 'ocupado'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mesa_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al finalizar la reserva.']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
