<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['idioma'])) {
    $_SESSION['idioma'] = $data['idioma'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Idioma no válido']);
}
