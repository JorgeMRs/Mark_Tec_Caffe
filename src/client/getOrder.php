<?php
require '../db/db_connect.php';
require '../auth/verifyToken.php';

$response = checkToken();

$user_id = $response['idCliente'];

$conn = getDbConnection();

$response = [];
try {
    // Obtener los pedidos del cliente junto con la fecha de cancelación (si existe)
    $sqlPedidos = "SELECT p.idPedido, p.fechaPedido, p.estado, p.total, p.fechaModificacion, c.fechaCancelacion 
    FROM pedido p
    LEFT JOIN cancelacionpedido c ON p.idPedido = c.idPedido
    WHERE p.idCliente=?";

    $stmtPedidos = $conn->prepare($sqlPedidos);
    $stmtPedidos->bind_param("i", $user_id);

    if ($stmtPedidos->execute()) {
        $resultPedidos = $stmtPedidos->get_result();

        while ($pedido = $resultPedidos->fetch_assoc()) {
            $idPedido = $pedido['idPedido'];

            // Obtener los productos de cada pedido
            $sqlProductos = "SELECT p.nombre, p.precio, pd.cantidad 
                             FROM producto p 
                             JOIN pedidodetalle pd ON p.idProducto = pd.idProducto 
                             WHERE pd.idPedido=?";
            $stmtProductos = $conn->prepare($sqlProductos);
            $stmtProductos->bind_param("i", $idPedido);
            $stmtProductos->execute();
            $resultProductos = $stmtProductos->get_result();

            $productos = [];
            while ($producto = $resultProductos->fetch_assoc()) {
                $productos[] = $producto;
            }

            $pedido['productos'] = $productos;
            $response[] = $pedido;

            $stmtProductos->close();
        }
    } else {
        $response['error'] = "Error ejecutando la consulta: " . $stmtPedidos->error;
    }
    $stmtPedidos->close();
} catch (Exception $e) {
    $response['error'] = 'Excepción capturada: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
