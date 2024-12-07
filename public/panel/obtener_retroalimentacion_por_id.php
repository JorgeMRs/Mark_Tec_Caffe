<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idRetroalimentacion = intval($_GET['id']);

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $query = "SELECT r.idRetroalimentacion, r.idCliente, c.nombre AS clienteNombre, c.apellido AS clienteApellido, r.nivelSatisfaccion, r.comentario 
              FROM retroalimentacion r
              JOIN cliente c ON r.idCliente = c.idCliente
              WHERE r.idRetroalimentacion = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idRetroalimentacion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = $result->fetch_assoc();
        $response['success'] = true;
    } else {
        $response['error'] = 'Retroalimentación no encontrada';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido o ID no proporcionado';
}

echo json_encode($response);
?>