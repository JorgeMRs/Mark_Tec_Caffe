<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
}

$idEmpleado = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idEmpleado <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de empleado inválido']);
    exit;
}

$query = "DELETE FROM empleado WHERE idEmpleado = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idEmpleado);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Empleado eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el empleado o ya estaba eliminado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el empleado: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
