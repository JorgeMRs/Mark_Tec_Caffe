```php
<?php
session_start();
require_once 'db_connect.php'; // Ajusta el path a tu conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
        exit();
    }

    // Obtener el precio del producto
    if ($stmt = $conn->prepare("SELECT precio FROM producto WHERE idProducto = ?")) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($product_price);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al obtener el precio del producto.']);
        exit();
    }

    // Verificar si el producto ya está en el carrito
    if ($stmt = $conn->prepare("SELECT cantidad FROM carritodetalle WHERE idCarrito = ? AND idProducto = ?")) {
        $stmt->bind_param("ii", $_SESSION['idCarrito'], $product_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Si el producto ya está en el carrito, actualizar la cantidad
            $stmt->bind_result($existing_quantity);
            $stmt->fetch();
            $new_quantity = $existing_quantity + $quantity;

            if ($stmt = $conn->prepare("UPDATE carritodetalle SET cantidad = ?, precio = ? WHERE idCarrito = ? AND idProducto = ?")) {
                $stmt->bind_param("idii", $new_quantity, $product_price, $_SESSION['idCarrito'], $product_id);
                $stmt->execute();
            }
        } else {
            // Si el producto no está en el carrito, insertarlo
            if ($stmt = $conn->prepare("INSERT INTO carritodetalle (idCarrito, idProducto, cantidad, precio) VALUES (?, ?, ?, ?)")) {
                $stmt->bind_param("iiid", $_SESSION['idCarrito'], $product_id, $quantity, $product_price);
                $stmt->execute();
            }
        }

        $stmt->close();
    }

    $conn->close();
    echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
}
?>