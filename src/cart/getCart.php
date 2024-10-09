<?php
require_once '../db/db_connect.php'; // Ajusta el path a tu conexión a la base de datos
include '../auth/verifyToken.php';
use Stichoza\GoogleTranslate\GoogleTranslate;

header('Content-Type: application/json');

$response = checkToken();
$user_id = $response['idCliente']; 

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
    exit();
}

// Obtener el idioma desde el parámetro de la consulta
$lang = $_GET['lang'] ?? 'es'; // Si no se proporciona, por defecto es español

function translateText($text, $lang) {
    // Si el idioma es español, retornar el texto original
    if ($lang === 'es') {
        return $text;
    }

    $tr = new GoogleTranslate($lang);
    try {
        return $tr->translate($text); // Intenta traducir el texto
    } catch (Exception $e) {
        // Retorna el texto original si hay un error
        return $text; 
    }
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
        // Traducir nombre y descripción solo si el idioma no es español
        $row['nombre'] = translateText($row['nombre'], $lang);
        $row['descripcion'] = translateText($row['descripcion'], $lang);

        $products[] = $row;
    }

    echo json_encode($products);
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta.']);
}

$conn->close();
?>
