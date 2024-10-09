<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();

$employee_id = $response['idEmpleado']; 
$role = $response['rol'];

if (isset($_GET['id'])) {
    $pedidoId = intval($_GET['id']);

    $conn = getDbConnection();
    if (!$conn) {
        echo json_encode(['error' => 'Error de conexión a la base de datos']);
        exit();
    }

    $query = "SELECT p.idPedido, p.numeroPedidoSucursal, p.tipoPedido, p.total, p.horaRecogida, s.nombre AS sucursal, m.numero AS numeroMesa, p.estado, p.notas, p.metodoPago, p.fechaPedido
              FROM pedido p
              LEFT JOIN sucursal s ON p.idSucursal = s.idSucursal
              LEFT JOIN mesa m ON p.idMesa = m.idMesa
              WHERE p.idPedido = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $pedidoId);

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Error en la consulta: ' . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();

    // Fetch products associated with the order
    $queryProductos = "SELECT p.nombre, p.precio, pd.cantidad 
                       FROM producto p 
                       JOIN pedidodetalle pd ON p.idProducto = pd.idProducto 
                       WHERE pd.idPedido=?";
    $stmtProductos = $conn->prepare($queryProductos);
    $stmtProductos->bind_param('i', $pedidoId);
    $stmtProductos->execute();
    $resultProductos = $stmtProductos->get_result();

    $productos = [];
    while ($producto = $resultProductos->fetch_assoc()) {
        $productos[] = $producto;
    }

    // Include products in the response
    $pedido['productos'] = $productos;

    // Ensure total is a number
    if (isset($pedido['total'])) {
        $pedido['total'] = floatval($pedido['total']);
    }

    echo json_encode($pedido);

    $stmt->close();
    $stmtProductos->close();
    $conn->close();
}
?>
