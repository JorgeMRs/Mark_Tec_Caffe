<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está conectado como cliente o mozo
$isLoggedIn = isset($_SESSION['user_id']) || (isset($_SESSION['employee_id']) && $_SESSION['role'] === 'Mozo');

echo json_encode([
    'loggedIn' => $isLoggedIn,
    'userId' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'employeeId' => isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : null,
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : null
]);
