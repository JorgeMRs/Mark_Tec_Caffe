<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idProducto = intval($_POST['idProducto']);
    $nombreProducto = $_POST['nombreProducto'];
    $cantidad = intval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);
    $idCategoria = intval($_POST['idCategoria']);

    // Validar los datos recibidos
    if (empty($nombreProducto) || $cantidad < 0 || $precio < 0 || empty($idCategoria)) {
        die('Error: Datos inválidos.');
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }

    // Actualizar los datos en la base de datos
    $query = "UPDATE producto SET nombre = ?, stock = ?, precio = ?, idCategoria = ? WHERE idProducto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sidii', $nombreProducto, $cantidad, $precio, $idCategoria, $idProducto);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Inventario actualizado correctamente.']);
      
    } else {
        echo 'Error al actualizar el inventario.';
    }

    $stmt->close();
    $conn->close();
} else {
    die('Error: Método de solicitud no permitido.');
}
?>