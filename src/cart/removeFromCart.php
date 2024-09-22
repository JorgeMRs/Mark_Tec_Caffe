<?php
include '../db/db_connect.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos
include '../auth/verifyToken.php';

header('Content-Type: application/json');

$response = checkToken();

$user_id = $response['idCliente']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtén los datos de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['product_id']; 


        $conn = getDbConnection();

        // Prepara la consulta para obtener el idCarrito del cliente (único carrito)
        if ($stmt = $conn->prepare("SELECT idCarrito FROM carrito WHERE idCliente = ? LIMIT 1")) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($cart_id);
            $stmt->fetch();
            $stmt->close();

            error_log("Cart ID: " . $cart_id);

            // Prepara la consulta para eliminar el producto del carrito
            if ($stmt = $conn->prepare("DELETE FROM carritodetalle WHERE idCarrito = ? AND idProducto = ?")) {
                $stmt->bind_param("ii", $cart_id, $product_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto']);
                }

                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error en la consulta para eliminar el producto']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al obtener el idCarrito']);
        }

        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
