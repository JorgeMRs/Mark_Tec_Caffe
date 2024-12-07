<?php
require_once '../../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta
use Stichoza\GoogleTranslate\GoogleTranslate;

header("Content-Type: application/json");

try {
    // Obtener el cuerpo de la solicitud JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Verificar si el texto y el idioma están establecidos
    if (isset($data['text']) && isset($data['idioma'])) {
        $text = $data['text'];
        $idioma = $data['idioma'];

        // Realizar la traducción
        $translatedText = GoogleTranslate::trans($text, $idioma);

        echo json_encode([
            'status' => 'success',
            'translated' => $translatedText
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Texto o idioma no válido'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
