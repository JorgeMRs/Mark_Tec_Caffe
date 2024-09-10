<?php
require '../vendor/autoload.php'; // Ajusta la ruta según sea necesario
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

function generateQrCode($data): string
{
    $writer = new PngWriter();
    $qrCode = Builder::create()
        ->writer($writer)
        ->data($data)
        ->size(400)
        ->margin(10)
        ->build();
    
    // Definir el directorio en el sistema de archivos para guardar los QR codes
    $directory = '/var/www/cafesabrosos/src/qrcodes';
    
    // Asegurarse de que el directorio exista
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true); // Crea el directorio si no existe
    }
    
    // Definir el nombre del archivo QR code
    $fileName = 'qr_code_' . uniqid() . '.png';
    $filePath = $directory . '/' . $fileName;
    
    // Guardar el QR code en el archivo especificado
    $qrCode->saveToFile($filePath);

    // Retorna la URL pública para usar en el correo electrónico
    return 'https://cafesabrosos.myvnc.com/src/qrcodes/' . $fileName;
}
function sendOrderConfirmationEmail($orderId, $email): bool
{
    $mail = new PHPMailer(true);

    // Obtener detalles del pedido usando $orderId
    $orderDetails = getOrderDetails($orderId);

    if (!$orderDetails) {
        return false; // Si no hay detalles de pedido, no se envía el correo
    }

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL'];
        $mail->Password = $_ENV['SMTPPASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipiente
        $mail->setFrom('no-reply@cafesabrosos.myvnc.com', 'Café Sabrosos');
        $mail->addAddress($email);

        // Contenido
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirmación de tu Pedido #' . $orderDetails['numeroPedidoCliente'];
        
        // Obtener la URL pública del QR code
        $qrFileUrl = generateQrCode($orderDetails['codigoVerificacion']);

        // Obtener el archivo QR code para adjuntar
        $qrFilePath = str_replace('https://cafesabrosos.myvnc.com/src/qrcodes/', '/var/www/cafesabrosos/src/qrcodes/', $qrFileUrl);
        
        // Generar el cuerpo del correo
        $mail->Body = getOrderEmailBody($orderDetails, $qrFileUrl);

        // Adjuntar el archivo QR code
        $mail->addAttachment($qrFilePath, 'codigo_qr.png');
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

function getOrderDetails($orderId)
{
    try {
        $conn = getDbConnection();

        // Obtener los productos del pedido y el numeroPedidoCliente
        $stmt = $conn->prepare("SELECT p.nombre, p.imagen, pd.cantidad, pd.precio, (pd.cantidad * pd.precio) as totalProducto
                                FROM pedidodetalle pd 
                                JOIN producto p ON pd.idProducto = p.idProducto 
                                JOIN pedido o ON pd.idPedido = o.idPedido
                                WHERE pd.idPedido = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $orderDetails['productos'][] = $row;
        }

        if (empty($orderDetails['productos'])) {
            error_log("No se encontraron productos para el pedido con ID $orderId.");
            throw new Exception('No se encontraron productos para este pedido.');
        }

        $stmt->close();

        // Obtener el numeroPedidoCliente, codigoVerificacion, tipoPedido y detalles de la sucursal
        $stmt = $conn->prepare("SELECT numeroPedidoCliente, codigoVerificacion, tipoPedido, s.nombre, s.direccion, s.pais, s.ciudad, s.tel 
                                FROM pedido p 
                                LEFT JOIN sucursal s ON p.idSucursal = s.idSucursal
                                WHERE p.idPedido = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->bind_result($numeroPedidoCliente, $codigoVerificacion, $tipoPedido, $sucursalNombre, $sucursalDireccion, $sucursalPais, $sucursalCiudad, $sucursalTel);
        $stmt->fetch();
        $stmt->close();

        if (!$numeroPedidoCliente) {
            error_log("No se encontró el número de pedido de usuario para el pedido con ID $orderId.");
            throw new Exception('No se encontró el número de pedido para este pedido.');
        }

        // Calcular el subtotal
        $stmt = $conn->prepare("SELECT SUM(cantidad * precio) AS subtotal FROM pedidodetalle WHERE idPedido = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->bind_result($subtotal);
        $stmt->fetch();
        $stmt->close();

        if ($subtotal <= 0) {
            error_log("Subtotal calculado es cero o negativo para el pedido con ID $orderId.");
            throw new Exception('El pedido está vacío o no tiene productos válidos.');
        }

        // Calcular el IVA y el total
        $ivaRate = 0.20; // 20%
        $tax = $subtotal * $ivaRate;
        $total = $subtotal + $tax;

        $orderDetails['subtotal'] = $subtotal;
        $orderDetails['tax'] = $tax;
        $orderDetails['total'] = $total;
        $orderDetails['numeroPedidoCliente'] = $numeroPedidoCliente;
        $orderDetails['codigoVerificacion'] = $codigoVerificacion;
        $orderDetails['tipoPedido'] = $tipoPedido;
        $orderDetails['sucursal'] = [
            'nombre' => $sucursalNombre,
            'direccion' => $sucursalDireccion,
            'pais' => $sucursalPais,
            'ciudad' => $sucursalCiudad,
            'tel' => $sucursalTel,
        ];

        $conn->close();
        return $orderDetails;
    } catch (Exception $e) {
        error_log('Error al obtener los detalles del pedido: ' . $e->getMessage());
        return null;
    }
}

function getOrderEmailBody($orderDetails, $qrFileUrl): string
{
    $timestamp = date('Y-m-d H:i:s');
    $uniqueContent = "<p>Fecha del pedido: $timestamp</p>";
    $productosHTML = '';
    $baseImageUrl = 'https://cafesabrosos.myvnc.com';

    foreach ($orderDetails['productos'] as $producto) {
        $imagePath = rawurlencode(dirname($producto['imagen'])) . '/' . rawurlencode(basename($producto['imagen']));
        $imageUrl = !empty($imagePath) ? $baseImageUrl . '/' . $imagePath : $baseImageUrl . '/imagen_por_defecto.jpg';

        // Formatear los valores numéricos a formato de moneda (2 decimales)
        $precioFormateado = number_format($producto['precio'], 2, '.', ',');
        $totalProductoFormateado = number_format($producto['totalProducto'], 2, '.', ',');

        $productosHTML .= "
            <tr>
                <td><img src='{$imageUrl}' alt='{$producto['nombre']}' style='width: 50px; height: 50px;'></td>
                <td>{$producto['nombre']}</td>
                <td>{$producto['cantidad']}</td>
                <td>\${$precioFormateado}</td>
                <td>\${$totalProductoFormateado}</td>
            </tr>
        ";
    }

    // Formatear subtotal y total
    $subtotalFormateado = number_format($orderDetails['subtotal'], 2, '.', ',');
    $totalFormateado = number_format($orderDetails['total'], 2, '.', ',');

    // Obtener información de la sucursal
    $sucursal = $orderDetails['sucursal'];
    $sucursalInfo = $sucursal ? "<p><strong>Sucursal:</strong> {$sucursal['nombre']}</p>
                                <p><strong>Dirección:</strong> {$sucursal['direccion']}, {$sucursal['ciudad']}, {$sucursal['pais']}</p>
                                <p><strong>Teléfono:</strong> {$sucursal['tel']}</p>" : '';

    // Agregar código de verificación solo para pedidos para llevar
    $codigoVerificacionHTML = '';
    
        $codigoVerificacionHTML = "
            <p><strong>Código de Verificación:</strong> {$orderDetails['codigoVerificacion']}</p>
            <p style='text-align: center;'>
                <img src='{$qrFileUrl}' alt='Código QR' style='width: 300px; height: 300px;'>
            </p>
            <p style='width: 700px;'><strong>Por favor, presenta el código de verificación o este código QR al mozo cuando llegues a la sucursal.</strong></p>
        ";
    

    return "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                color: #333;
                text-align: center;
            }
            h1, p, strong {
                color: #333;
            }
            .container {
                background-color: #ffffff;
                padding: 20px;
                max-width: 600px;
                color: #333;
                margin: 20px auto;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            table {
                width: 100%;
                border-collapse: collapse;
                color: #333;
            }
            th, td {
                color: #333;
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }
            img {
                width: 50px;
                height: 50px;
                object-fit: cover;
            }
            .footer {
                color: #333;
                color: #888;
                text-align: center;
                font-size: 14px;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
         $uniqueContent
            <h1>Gracias por tu Pedido #{$orderDetails['numeroPedidoCliente']}</h1>
            <p>Tu pedido ha sido confirmado. Aquí están los detalles:</p>
            $sucursalInfo
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    $productosHTML
                </tbody>
            </table>
            <p><strong>Subtotal:</strong> \${$subtotalFormateado}<br>
               <strong>Total (incluyendo IVA):</strong> \${$totalFormateado}</p>
            $codigoVerificacionHTML
            <div class='footer'>
                <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>";
}
?>
