<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregarCategoria') {
    $nombreCategoria = $_POST['nombreCategoria'];
    $categoriaImagen = $_FILES['categoriaImagen'];

    // Validar datos
    if (empty($nombreCategoria)) {
        $response['message'] = 'El nombre de la categoría es obligatorio.';
    } else {
        // Preparar la consulta de inserción
        $query = "INSERT INTO categoria (nombre, imagen) VALUES (?, ?)";

        // Ruta por defecto para la imagen
        $imagenPath = null;

        if ($categoriaImagen['error'] === UPLOAD_ERR_OK) {
            $imagenPath = '/public/assets/img/categorias/' . basename($categoriaImagen['name']);
            move_uploaded_file($categoriaImagen['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagenPath);
        }

        $stmt = $conn->prepare($query);
        if ($imagenPath) {
            $stmt->bind_param('ss', $nombreCategoria, $imagenPath);
        } else {
            $stmt->bind_param('s', $nombreCategoria);
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Categoría agregada exitosamente.';
        } else {
            $response['message'] = 'Error al agregar la categoría: ' . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
