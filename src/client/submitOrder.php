<?php
include '../db/db_connect.php'; // Ajusta la ruta según tu estructura de directorios
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


$response = checkToken();

$user_id = $response['idCliente']; 

$response = array('success' => false, 'message' => '');

// Clave secreta utilizada para firmar el JWT
$secretKey = $_ENV['JWT_SECRET'];

// Obtener el token CSRF del formulario
$csrfTokenFromForm = $_POST['csrf_token'] ?? '';

// Obtener el token CSRF de la cookie
$csrfTokenFromCookie = $_COOKIE['csrf_token'] ?? '';

if (!$csrfTokenFromForm || !$csrfTokenFromCookie || $csrfTokenFromForm !== $csrfTokenFromCookie) {
    echo json_encode(['success' => false, 'message' => 'Error de validación CSRF.']);
    http_response_code(400); // Respuesta con código de error
    exit();
}

if (!$csrfTokenFromCookie) {
    // Si la cookie csrf_token no está presente, responder con un mensaje claro y terminar la solicitud
    echo json_encode(['success' => false, 'message' => 'Tiempo agotado. Por favor, recarga la página e inténtalo de nuevo.']);
    http_response_code(400);
    exit();
}

try {
    // Verificar el JWT decodificando el token CSRF de la cookie
    $decoded = JWT::decode($csrfTokenFromCookie, new Key($secretKey, 'HS256'));

    // Verificar si el token ha expirado
    if ($decoded->exp < time()) {
        echo json_encode(['success' => false, 'message' => 'El token CSRF ha caducado. Por favor, recarga la página e inténtalo de nuevo.']);
        http_response_code(400);
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Token CSRF no válido o malformado. Por favor, recarga la página e inténtalo de nuevo.']);
    http_response_code(400);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no permitido.');
    }

    $orderType = $_POST['orderType'] ?? null;
    $orderNotes = $_POST['orderNotes'] ?? null;
    $pickupTime = $_POST['pickupTime'] ?? null;
    $branchId = $_POST['branch'] ?? null;

    if ($orderType === null || $orderNotes === null) {
        throw new Exception('Faltan datos del pedido.');
    }
    
    if (empty($branchId)) {
        throw new Exception('Debe seleccionar una sucursal.');
    }
    
    // Generar el código de verificación
    $codigoVerificacion = 'PEDIDO' . str_pad(substr(md5(uniqid(rand(), true)), 0, 6), 6, '0', STR_PAD_LEFT);

    if ($orderType === 'Para llevar') {
        // Validar que se haya especificado una hora y que esté en formato HH:MM
        if (empty($pickupTime)) {
            throw new Exception('Debe especificar una hora de recogida válida para pedidos "Para llevar".');
        }
        // La hora ya está en formato HH:MM:SS en el formulario, no es necesario convertir
        error_log("Hora de recogida recibida: $pickupTime"); // Depurar valor
    } else {
        $pickupTime = null;
    }

    $conn = getDbConnection();
    $conn->begin_transaction();

    // Obtener el último idCarrito del cliente
    $stmt = $conn->prepare("SELECT idCarrito FROM carrito WHERE idCliente = ? ORDER BY idCarrito DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($cartId);
    $stmt->fetch();
    $stmt->close();

    if (!$cartId) {
        throw new Exception('No se encontró el carrito.');
    }

    // Calcular el subtotal
    $stmt = $conn->prepare("SELECT SUM(cantidad * precio) AS subtotal FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $stmt->bind_result($subtotal);
    $stmt->fetch();
    $stmt->close();

    if ($subtotal <= 0) {
        throw new Exception('El carrito está vacío o no tiene productos válidos.');
    }

    // Calcular el IVA y el total
    $ivaRate = 0.20;
    $tax = $subtotal * $ivaRate;
    $total = $subtotal + $tax;

    // Actualizar o insertar el número de pedido para la sucursal seleccionada
    $stmt = $conn->prepare("SELECT numeroPedido FROM numeropedidosucursal WHERE idSucursal = ?");
    $stmt->bind_param("i", $branchId);
    $stmt->execute();
    $stmt->bind_result($numeroPedidoSucursal);
    $stmt->fetch();
    $stmt->close();

    if ($numeroPedidoSucursal === null) {
        // Si no existe, inicializamos en 1
        $numeroPedidoSucursal = 1;
        $stmt = $conn->prepare("INSERT INTO numeropedidosucursal (idSucursal, numeroPedido) VALUES (?, ?)");
        $stmt->bind_param("ii", $branchId, $numeroPedidoSucursal);
    } else {
        // Si existe, incrementamos el número de pedido
        $numeroPedidoSucursal++;
        $stmt = $conn->prepare("UPDATE numeropedidosucursal SET numeroPedido = ? WHERE idSucursal = ?");
        $stmt->bind_param("ii", $numeroPedidoSucursal, $branchId);
    }

    if (!$stmt->execute()) {
        throw new Exception('No se pudo actualizar el número de pedido para la sucursal.');
    }
    $stmt->close();


    // Obtener el último número de pedido para este usuario
    $stmt = $conn->prepare("SELECT COALESCE(MAX(numeroPedidoCliente), 0) AS ultimoNumero FROM pedido WHERE idCliente = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($ultimoNumeroPedido);
    $stmt->fetch();
    $stmt->close();

    $numeroPedidoCliente = $ultimoNumeroPedido + 1;
    error_log("Hora de recogida antes de la inserción: $pickupTime");

    // Insertar el nuevo pedido con el número de pedido específico del usuario
    $stmt = $conn->prepare("INSERT INTO pedido (idCliente, idCarrito, idSucursal, tipoPedido, notas, horaRecogida, metodoPago, total, codigoVerificacion, numeroPedidoCliente, numeroPedidoSucursal) VALUES (?, ?, ?, ?, ?, ?, 'Tarjeta', ?, ?, ?, ?)");
    $stmt->bind_param("iiisssdsii", $user_id, $cartId, $branchId, $orderType, $orderNotes, $pickupTime, $total, $codigoVerificacion, $numeroPedidoCliente, $numeroPedidoSucursal);
    
    if (!$stmt->execute()) {
        throw new Exception('No se pudo realizar el pedido.');
    }

    $orderId = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO pedidodetalle (idPedido, idProducto, cantidad, precio) SELECT ?, idProducto, cantidad, precio FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("ii", $orderId, $cartId);
    if (!$stmt->execute()) {
        throw new Exception('No se pudieron insertar los detalles del pedido.');
    }

    $stmt = $conn->prepare("DELETE FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("i", $cartId);
    if (!$stmt->execute()) {
        throw new Exception('No se pudieron eliminar los detalles del carrito.');
    }

    $conn->commit();
    $response['success'] = true;
    $response['orderId'] = $orderId;
    $response['numeroPedidoCliente'] = $numeroPedidoCliente;
    $response['codigoVerificacion'] = $codigoVerificacion;

    setcookie('csrf_token', '', time() - 3600, '/');

} catch (Exception $e) {
    if (isset($conn) && $conn->errno) {
        $conn->rollback();
    }
    $response['message'] = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

echo json_encode($response);
?>
