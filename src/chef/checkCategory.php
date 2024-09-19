<?php
// checkCategory.php
session_start();
require_once '../db/db_connect.php';

$response = array('success' => false, 'productLinked' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        // Verificar si la categoría tiene productos vinculados a pedidos
        $query = "
            SELECT COUNT(*) AS productLinked
            FROM producto p
            JOIN pedidodetalle pd ON p.idProducto = pd.idProducto
            WHERE p.idCategoria = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if ($data['productLinked'] > 0) {
            // Hay productos vinculados a pedidos
            $response['success'] = true;
            $response['productLinked'] = true;
            $response['message'] = 'La categoría tiene productos vinculados a pedidos.';
        } else {
            // No hay productos vinculados a pedidos
            $response['success'] = true;
            $response['productLinked'] = false;
            $response['message'] = 'La categoría no tiene productos vinculados a pedidos.';
        }
    } else {
        throw new Exception('Solicitud inválida. Falta el ID de la categoría.');
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
