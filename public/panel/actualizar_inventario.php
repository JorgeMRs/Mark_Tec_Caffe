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

    // Validar los datos recibidos
    if (empty($idProducto) || empty($nombreProducto) || $cantidad < 0 || $precio < 0 || empty($idCategoria)) {
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

    // Actualizar los datos del producto en la base de datos
    $query = "UPDATE producto SET nombre = ?, stock = ?, precio = ?, idCategoria = ? WHERE idProducto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sidii', $nombreProducto, $cantidad, $precio, $idCategoria, $idProducto);

    if ($stmt->execute()) {
        // Obtener el nombre de la categoría
        $queryCategoria = "SELECT nombre FROM categoria WHERE idCategoria = ?";
        $stmtCategoria = $conn->prepare($queryCategoria);
        $stmtCategoria->bind_param('i', $idCategoria);
        $stmtCategoria->execute();
        $resultCategoria = $stmtCategoria->get_result();
        $categoria = $resultCategoria->fetch_assoc();

        $response['success'] = true;
        $response['message'] = 'Inventario actualizado correctamente.';
        $response['id'] = $idProducto;
        $response['campoModificado'] = 'category'; // Cambia esto según el campo que se haya modificado
        $response['valorModificado'] = $categoria['nombre']; // Devuelve el nombre de la categoría
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