<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function getDbConnection() {
    $host = $_ENV['DB_HOST'];
    $port = 3306;  // Puerto por defecto de MySQL
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $database = $_ENV['DB_NAME'];

    // Crear conexión
    $conn = new mysqli($host, $user, $password, $database, $port);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $conn->connect_error);
    }

    return $conn;
}
?>