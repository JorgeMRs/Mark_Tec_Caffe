<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idProducto = isset($_POST['idProducto']) ? intval($_POST['idProducto']) : null;
    $nombreProducto = isset($_POST['nombreProducto']) ? $_POST['nombreProducto'] : null;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : null;
    $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : null;
    $idCategoria = isset($_POST['idCategoria']) ? intval($_POST['idCategoria']) : null;

    // Mensajes de depuración
    error_log("idProducto: " . $idProducto);
    error_log("nombreProducto: " . $nombreProducto);
    error_log("cantidad: " . $cantidad);
    error_log("precio: " . $precio);
    error_log("idCategoria: " . $idCategoria);

    // Validar los datos recibidos
    if (empty($idProducto) || empty($nombreProducto) || empty($cantidad) || empty($precio) || empty($idCategoria)) {
        $response['error'] = 'Faltan datos necesarios';
        echo json_encode($response);
        exit;
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    // Actualizar los datos del inventario en la base de datos
    $query = "UPDATE producto SET nombre = ?, stock = ?, precio = ?, idCategoria = ? WHERE idProducto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sddii', $nombreProducto, $cantidad, $precio, $idCategoria, $idProducto);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Inventario actualizado correctamente.';
        $response['id'] = $idProducto;
        $response['camposModificados'] = [
            'nombreProducto' => $nombreProducto,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'idCategoria' => $idCategoria
        ];
    } else {
        $response['error'] = 'Error al actualizar el inventario.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido';
}

echo json_encode($response);
?>