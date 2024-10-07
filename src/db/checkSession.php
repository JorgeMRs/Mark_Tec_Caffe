<?php
require '../../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta
require '../auth/verifyToken.php'; // Ruta al archivo que contiene la función verifyToken

header('Content-Type: application/json');

$response = checkToken(); 

echo json_encode([
    'loggedIn' => $response['success'], // Indica si el usuario está conectado
    'userId' => $response['success'] ? $response['idCliente'] : null, // idCliente si está conectado
    'employeeId' => $response['success'] && $response['role'] === 'employee' ? $response['idEmpleado'] : null, // idEmpleado si es un empleado
    'role' => $response['success'] ? $response['role'] : null // Rol si está conectado
]);
?>
