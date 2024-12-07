<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['idioma'])) {
    // Actualizar el idioma en la sesión
    $_SESSION['idioma'] = $data['idioma'];
    echo json_encode(['status' => 'success']);
} else {
    // Si no se recibe el idioma, devolver el idioma actual
    echo json_encode(['status' => 'success', 'idioma' => $_SESSION['idioma'] ?? 'es']); // Valor por defecto es 'es'
}
