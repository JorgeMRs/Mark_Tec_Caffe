<?php
require_once 'db_connect.php'; // Ajusta la ruta según la ubicación de tu archivo

// Obtener la conexión a la base de datos
$conn = getDbConnection();

// ID del cliente
$user_id = 10;

// Preparar y ejecutar la consulta
if ($stmt = $conn->prepare("SELECT p.nombre, p.imagen, p.descripcion, cd.cantidad, cd.precio 
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
} else {
    echo "Error en la consulta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>