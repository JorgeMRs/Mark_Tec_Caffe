<?php
include '../db/db_connect.php';
include '../auth/verifyToken.php';

$response = array('success' => false, 'message' => ''); // Inicializar la respuesta
$responseToken = checkToken();
$user_id = $responseToken['idCliente']; 

try {
    // Obtener datos del producto desde la solicitud POST
    $idProducto = $_POST['producto_id'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;

    error_log("ID Producto: " . $idProducto);
    error_log("Cantidad: " . $cantidad);

    if (!$idProducto || !$cantidad || !is_numeric($cantidad) || $cantidad <= 0) {
        http_response_code(400);
        $response['message'] = 'Datos inválidos.';
        echo json_encode($response);
        exit;
    }

    // Obtener conexión a la base de datos
    $conn = getDbConnection();
    $conn->begin_transaction();

    // Seleccionar el carrito más antiguo asociado al cliente
    $queryCarrito = $conn->prepare('
        SELECT idCarrito 
        FROM carrito 
        WHERE idCliente = ? 
        ORDER BY fechaCreacion ASC 
        LIMIT 1
    ');
    $queryCarrito->bind_param('i', $user_id);
    $queryCarrito->execute();
    $resultCarrito = $queryCarrito->get_result();
    $carrito = $resultCarrito->fetch_assoc();

    // Si no hay un carrito existente, crear uno nuevo
    if ($carrito) {
        $idCarrito = $carrito['idCarrito'];
    } else {
        $queryInsertCarrito = $conn->prepare('
            INSERT INTO carrito (idCliente, fechaCreacion) 
            VALUES (?, NOW())
        ');
        $queryInsertCarrito->bind_param('i', $user_id);
        $queryInsertCarrito->execute();
        $idCarrito = $conn->insert_id;
    }

    // Verificar si el producto ya está en el carrito
    $queryDetalle = $conn->prepare('
        SELECT cantidad 
        FROM carritodetalle 
        WHERE idCarrito = ? AND idProducto = ?
    ');
    $queryDetalle->bind_param('ii', $idCarrito, $idProducto);
    $queryDetalle->execute();
    $resultDetalle = $queryDetalle->get_result();
    $detalle = $resultDetalle->fetch_assoc();

    if ($detalle) {
        // Verificar si la suma supera el límite de 9
        $cantidadActual = $detalle['cantidad'];
        if ($cantidadActual + $cantidad > 9) {
            throw new Exception('No se puede agregar al carrito: cantidad máxima excedida (9 unidades) para este producto.');
        }

        // Actualizar la cantidad sumando a la cantidad actual
        $nuevaCantidad = $cantidadActual + $cantidad;
        $queryUpdate = $conn->prepare('
            UPDATE carritodetalle 
            SET cantidad = ? 
            WHERE idCarrito = ? AND idProducto = ?
        ');
        $queryUpdate->bind_param('iii', $nuevaCantidad, $idCarrito, $idProducto);
        $queryUpdate->execute();
    } else {
        // Limitar la cantidad a 9 en la inserción si es un producto nuevo
        $cantidad = min(9, $cantidad);
        $queryInsertDetalle = $conn->prepare('
            INSERT INTO carritodetalle (idCarrito, idProducto, cantidad, precio) 
            VALUES (?, ?, ?, (SELECT precio FROM producto WHERE idProducto = ?))
        ');
        $queryInsertDetalle->bind_param('iiii', $idCarrito, $idProducto, $cantidad, $idProducto);
        $queryInsertDetalle->execute();
    }

    // Confirmar transacción
    $conn->commit();

    // Actualizar la respuesta de éxito
    $response['success'] = true;
    $response['message'] = 'Producto agregado al carrito.';
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    http_response_code(500);
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
}

// Devolver la respuesta en formato JSON
echo json_encode($response);

?>
