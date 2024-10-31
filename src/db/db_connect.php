<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

/**
 * @throws Exception
 */

// Función para obtener la conexión a la base de datos
function getDbConnection(): mysqli
{
    $host = 'localhost'; // El valor del host debe ser 'localhost'
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $database = $_ENV['DB_NAME']; // Nombre de la base de datos
    $socket = '/var/lib/mysql/mysql.sock'; // Ruta al socket Unix
    
    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $mysqli->connect_error);
    }

    return $mysqli;
}

// Ejemplo de uso
try {
    $connection = getDbConnection();
} catch (Exception $e) {
    echo $e->getMessage();
}
