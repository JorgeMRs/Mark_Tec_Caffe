<?php
require_once 'db_connect.php'; // Ajusta el path a tu conexión a la base de datos

header('Content-Type: application/json');

$user_id = $_GET['user_id'];

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
    exit();
}


if ($stmt = $conn->prepare("SELECT p.idProducto, p.nombre, p.imagen, p.descripcion, cd.cantidad, cd.precio 
                            FROM carritodetalle cd 
                            JOIN producto p ON cd.idProducto = p.idProducto 
                            JOIN carrito c ON cd.idCarrito = c.idCarrito 
                            WHERE c.idCliente = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta.']);
}

$conn->close();
?>