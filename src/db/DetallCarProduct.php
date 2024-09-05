<?php
session_start(); // Iniciar la sesión
include 'db_connect.php';

try {
    // Obtener conexión a la base de datos
    $conn = getDbConnection();

    // Obtener el idCarrito desde la query string
    $idCarrito = $_GET['idCarrito'];

    // Obtener el user_id de la sesión
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        throw new Exception('Usuario no autenticado.');
    }

    // Preparar y ejecutar la consulta SQL para traer los datos de la tabla carritodetalle filtrados por idCarrito y user_id
    $sql = "SELECT * FROM carritodetalle WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idCarrito, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un array para guardar los datos en la variable Cardetallid
    $Cardetallid = [];  
    while ($row = $result->fetch_assoc()) {
        $Cardetallid[] = $row;
    }

    // Crear un array para guardar solo los datos de idProducto
    $idproductcar = [];
    foreach ($Cardetallid as $row) {
        $idproductcar[] = $row['idProducto'];
    }

    // Convertir el array de IDs en una cadena separada por comas
    $idproductcar_str = implode(',', array_map('intval', $idproductcar));

    // Preparar y ejecutar la consulta SQL para traer los datos de la tabla producto
    $sql = "SELECT * FROM producto WHERE idProducto IN ($idproductcar_str)";
    $result = $conn->query($sql);

    // Crear un array para guardar los productos
    $productosCar = [];
    while ($row = $result->fetch_assoc()) {
        $productosCar[] = $row;
    }

    // Mostrar los productos en formato JSON
    echo json_encode($productosCar);

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Manejo de errores de conexión
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>