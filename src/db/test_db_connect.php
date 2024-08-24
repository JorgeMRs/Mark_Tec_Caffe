<?php
require_once 'db_connect.php';

try {
	$connection = getDbConnection();
	echo "Conexión exitosa a la base de datos.";
	$connection->close();
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}