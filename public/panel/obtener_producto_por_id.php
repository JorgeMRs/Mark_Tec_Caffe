<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

if (!isset($_GET['id'])) {
    die('Error: ID de producto no especificado.');
}

$idProducto = intval($_GET['id']);

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

$query = "SELECT 
            p.idProducto AS idProducto,
            p.nombre AS nombreProducto,
            p.stock AS cantidad,
            p.precio AS precio
          FROM 
            producto p
          WHERE 
            p.idProducto = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idProducto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
    echo json_encode($producto);
} else {
    echo json_encode(['error' => 'Producto no encontrado']);
}

$stmt->close();
$conn->close();
?>