<?php

include 'db_connect.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

header('Content-Type: application/json');

// Verifica si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtén los datos de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['product_id']; // Asegúrate de que el nombre del campo es correcto

        
        // $user_id = $_SESSION['user_id']; // ID del usuario desde la sesión
        $user_id = 10; // ID del usuario de prueba
        // Obtener la conexión a la base de datos
        $conn = getDbConnection();

        // Prepara la consulta para obtener el idCarrito del usuario
        if ($stmt = $conn->prepare("SELECT idCarrito FROM carrito WHERE idCliente = ?")) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($cart_id);
            $stmt->fetch();
            $stmt->close();

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
                echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
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
?>