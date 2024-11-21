<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
}

$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idProducto <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
    exit;
}

// Prepara la consulta para desactivar el producto
$query = "UPDATE producto SET estadoActivacion = 0 WHERE idProducto = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idProducto);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto desactivado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto o ya estaba desactivado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al desactivar el producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
