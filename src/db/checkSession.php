<?php
require '../../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta
require '../auth/verifyToken.php'; // Ruta al archivo que contiene la función verifyToken

header('Content-Type: application/json');

$response = checkToken(); 

if (!$response['success']) {
    echo json_encode([
        'loggedIn' => false,
        'message' => $response['message'] // Devolvemos el mensaje de error en caso de fallo
    ]);
    exit();
}

echo json_encode([
    'loggedIn' => $response['success'],
    'userId' => $response['idCliente'] ?? null,
    'employeeId' => $response['idEmpleado'] ?? null,
    'role' => $response['role'] ?? null
]);

?>
