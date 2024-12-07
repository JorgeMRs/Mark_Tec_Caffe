<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();
$employee_id = $response['idEmpleado']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mesa_id = $_POST['mesa_id'] ?? null;

    if (!$mesa_id || !$employee_id) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para cambiar el estado.']);
        exit;
    }

    try {
        $conn = getDbConnection();

        // Cambia el estado de la reserva de 'reservado' a 'ocupado'
        $sql = "UPDATE reserva SET estado = 'ocupado' WHERE idMesa = ? AND estado = 'reservado'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mesa_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado.']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
