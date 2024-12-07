<?php
// Incluir el archivo de conexión a la base de datos
include '../../src/db/db_connect.php';

// Obtener los datos JSON enviados
$data = json_decode(file_get_contents('php://input'), true);
$qrData = $data['qrData'] ?? null;

if ($qrData) {
    $conn = getDbConnection();
    
    // Preparar la consulta para verificar el código del pedido
    $stmt = $conn->prepare("
    SELECT p.*, s.nombre AS nombreSucursal
    FROM pedido p
    JOIN sucursal s ON p.idSucursal = s.idSucursal 
    WHERE p.codigoVerificacion = ?
    ");
    $stmt->bind_param("s", $qrData);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El código QR existe en la base de datos
        $pedido = $result->fetch_assoc();
        
        // Obtener detalles del pedido con nombres de productos
        $stmtDetalles = $conn->prepare("
        SELECT pd.cantidad, pd.precio, pr.nombre AS nombreProducto
        FROM pedidodetalle pd
        JOIN producto pr ON pd.idProducto = pr.idProducto
        WHERE pd.idPedido = ?
        ");
        $stmtDetalles->bind_param("i", $pedido['idPedido']);
        $stmtDetalles->execute();
        $resultDetalles = $stmtDetalles->get_result();
        
        $detalles = [];
        while ($detalle = $resultDetalles->fetch_assoc()) {
            $detalles[] = $detalle;
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Código QR válido',
            'data' => $pedido,
            'detalles' => $detalles
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Código QR no válido']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún código QR']);
}

$conn->close();
?>
