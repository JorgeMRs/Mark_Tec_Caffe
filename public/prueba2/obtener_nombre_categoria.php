<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if (isset($_GET['idCategoria'])) {
    $idCategoria = intval($_GET['idCategoria']);

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $query = "SELECT nombre FROM categoria WHERE idCategoria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $categoria = $result->fetch_assoc();
        $response['success'] = true;
        $response['nombre'] = $categoria['nombre'];
    } else {
        $response['error'] = 'Categoría no encontrada';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'ID de categoría no proporcionado';
}

echo json_encode($response);
?>