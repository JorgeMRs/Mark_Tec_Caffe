<?php
define('BASE_PATH', __DIR__ . '/../');
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Cargar claves de entorno
$secretKey = $_ENV['JWT_SECRET'];
$encryptionKey = $_ENV['ENCRYPTION_KEY'];

// Retornar las claves como un array
return [
    'secretKey' => $secretKey,
    'encryptionKey' => $encryptionKey
];
?>
