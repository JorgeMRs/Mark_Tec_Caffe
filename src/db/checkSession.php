<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está conectado
echo json_encode([
    'loggedIn' => isset($_SESSION['user_id']),
    'userId' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null
]);