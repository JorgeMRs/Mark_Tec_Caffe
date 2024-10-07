<?php
include 'db_connect.php';
use Stichoza\GoogleTranslate\GoogleTranslate;

$lang = $_GET['lang'] ?? 'es';

try {
    $conn = getDbConnection();
    $idCategoria = $_GET['idCategoria'];

    // Preparar y ejecutar la consulta SQL con la condición de activación
    $sql = "SELECT idProducto, imagen, nombre, descripcion, precio 
            FROM producto 
            WHERE idCategoria = ? AND estadoActivacion = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        // Traducir nombre y descripción
        if ($lang !== 'es') {
            $row['nombre'] = GoogleTranslate::trans($row['nombre'], $lang, 'es');
            $row['descripcion'] = GoogleTranslate::trans($row['descripcion'], $lang, 'es');
        }
        $productos[] = $row;
    }
    
    // Devolver los productos en formato JSON
    echo json_encode($productos);

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Manejo de errores de conexión
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>
