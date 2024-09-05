<?php
require_once 'db_connect.php';

function obtenerProductos() {
	try {
		$connection = getDbConnection();
		
		// guardar todos los datos de la tabla producto en la variable $query
		$query = "SELECT * FROM producto";
		
		// ejecutar la consulta
		$result = $connection->query($query);
		
		// convertir el resultado de la consulta en un json
		$productos = $result->fetch_all(MYSQLI_ASSOC);
		
		$connection->close();
		
		return json_encode($productos);
	} catch (Exception $e) {
		return json_encode(['error' => $e->getMessage()]);
	}
}

// Establecer el encabezado de contenido como JSON
header('Content-Type: application/json');
echo obtenerProductos();

// Ejemplo de uso
echo obtenerProductos();
