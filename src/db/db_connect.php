<?php
require_once '/../../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('/../../');
$dotenv->load();

function getDbConnection() {
    $host = $_ENV['DB_HOST'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $database = $_ENV['DB_NAME'];

    // Crear conexión
    $mysqli = new mysqli($host, $user, $password, $database);

    // Verificar conexión
    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $mysqli->connect_error);
    }

    return $mysqli;
}
?>