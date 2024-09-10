<?php
require_once '../src/db/db_connect.php';
require_once '../src/email/codigoReservaEmail.php';
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

session_start();

$codigoReserva = isset($_GET['codigoReserva']) ? $_GET['codigoReserva'] : '';

$response = array('success' => false, 'message' => '');

if (empty($codigoReserva)) {
    header("Location: /public/error/403.html");
    exit();
}

function actualizarCodigoReserva($conn, $codigoReserva): string
{
    // Crear un nuevo código de reserva
    $nuevoCodigoReserva = 'MESA' . substr(md5(uniqid(rand(), true)), 0, 6);

    $sqlActualizarCodigo = "UPDATE reserva SET codigoReserva = ? WHERE codigoReserva = ?";
    $stmtActualizarCodigo = $conn->prepare($sqlActualizarCodigo);
    $stmtActualizarCodigo->bind_param("ss", $nuevoCodigoReserva, $codigoReserva);
    $stmtActualizarCodigo->execute();

    return $nuevoCodigoReserva;
}

try {
    $conn = getDbConnection();

    // Verificar si el código de reserva es válido
    $sqlVerificarCodigo = "SELECT COUNT(*) FROM reserva WHERE codigoReserva = ?";
    $stmtVerificarCodigo = $conn->prepare($sqlVerificarCodigo);
    $stmtVerificarCodigo->bind_param("s", $codigoReserva);
    $stmtVerificarCodigo->execute();
    $resultado = $stmtVerificarCodigo->get_result()->fetch_row()[0];

    if ($resultado <= 0) {
        header("Location: /public/error/403.html");
        exit();
    }

    // Obtener el correo electrónico del cliente desde la sesión
    $emailCliente = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

   
    if (!empty($emailCliente)) {
        $nuevoCodigoReserva = actualizarCodigoReserva($conn, $codigoReserva);

        // Generar el código QR basado en el nuevo código de reserva
        $qrResult = Builder::create()
            ->writer(new PngWriter()) 
            ->data($nuevoCodigoReserva) 
            ->size(300) 
            ->margin(10) 
            ->build();

        // Guardar la imagen del código QR en un archivo
        $qrFilePath = '../src/qrcodes/' . $nuevoCodigoReserva . '.png';
        $qrResult->saveToFile($qrFilePath);


        if (sendReservationEmail($emailCliente, $codigoReserva, $nuevoCodigoReserva, $qrFilePath)) {
            $response['success'] = true;
            $response['message'] = 'Tu reserva ha sido confirmada. Revisa tu correo electrónico para ver el nuevo código de reserva que te hemos enviado.';
            if (file_exists($qrFilePath)) {
                unlink($qrFilePath); 
            }
        } else {
            $response['message'] = 'Error al enviar el correo. Por favor, intenta de nuevo más tarde.';
        }
    } else {
        $response['message'] = 'No se ha recibido el correo electrónico del cliente. Por favor, contacta al soporte.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #DAA520;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
            background-color: #DAA520;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #1A0D0A;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Reserva Confirmada!</h1>
        <p><?php echo $response['message']; ?></p>
        <a href="/">Regresar al inicio</a>
    </div>
</body>
</html>
