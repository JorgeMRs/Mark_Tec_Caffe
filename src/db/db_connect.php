<?php
// $autoloadPath = __DIR__ . '/vendor/autoload.php';
$autoloadPath = '../../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("El archivo autoload.php no se encuentra en la ruta esperada: $autoloadPath");
}
require_once $autoloadPath;

use Dotenv\Dotenv;

// Ajustar la ruta al archivo .env
// $dotenv = Dotenv::createImmutable(__DIR__ .'/.env');
$dotenv = Dotenv::createImmutable('../../');

$dotenv->load();

function getDbConnection() {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $database = $_ENV['DB_NAME'];

    // Crear conexión
    $mysqli = new mysqli($host, $user, $password, $database, $port);

    // Verificar conexión
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    return $mysqli;
}
?>