<?php
include '../db/db_connect.php'; // Asegúrate de incluir tu archivo de conexión

try {
    $conn = getDbConnection();

    if (isset($_GET['ids'])) {
        $idList = $_GET['ids']; // Suponemos que esto es una cadena de IDs separados por comas

        // Crear la consulta SQL
        $sql = "SELECT idProducto, imagen, nombre, descripcion, precio 
                FROM producto 
                WHERE idProducto IN ($idList)";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Ejecutar la declaración
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        // Devolver los productos en formato JSON
        echo json_encode($productos);

        $stmt->close();
    } else {
        echo json_encode([]);
    }

    $conn->close();
} catch (Exception $e) {
    // Manejo de errores de conexión
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>
