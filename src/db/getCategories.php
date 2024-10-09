<?php
include 'db_connect.php';
use Stichoza\GoogleTranslate\GoogleTranslate;

require '../../vendor/autoload.php'; // Asegúrate de que esta línea esté incluida

try {
    $conn = getDbConnection();

    $sql = "SELECT idCategoria, nombre, imagen FROM categoria WHERE estadoActivacion = 1";
    $result = $conn->query($sql);

    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }

    // Obtener el idioma de la consulta (por defecto español)
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'es';

    // Traducir las categorías
    foreach ($categorias as &$categoria) {
        if ($lang !== 'es') { // No traducir si el idioma es español
            $categoria['nombre'] = GoogleTranslate::trans($categoria['nombre'], $lang, 'es');
        }
    }

    echo json_encode($categorias);

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage()); // Registra el error en el log
    echo json_encode(["error" => $e->getMessage()]);
}
?>
