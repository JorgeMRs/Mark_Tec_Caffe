<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendReservationEmail($to, $codigoReserva, $nuevoCodigoReserva, $qrFilePath): bool
{
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL'];
        $mail->Password = $_ENV['SMTPPASS']; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

     
        $mail->setFrom('no-reply@cafesabrosos.myvnc.com', 'Café Sabrosos');
        $mail->addAddress($to); 

        
        $mail->isHTML(true); 
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirmación de Reserva - Café Sabrosos';
        $mail->Body    = getReservationEmailBody($codigoReserva, $nuevoCodigoReserva);

        if (file_exists($qrFilePath)) {
            $mail->addAttachment($qrFilePath); // Adjuntar el QR
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Error en el correo: ' . $mail->ErrorInfo);
        return false;
    }
}

function getReservationEmailBody($codigoReserva, $nuevoCodigoReserva): string
{
    return "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                text-align: center;
            }
            .container {
                background-color: #ffffff;
                border-radius: 8px;
                padding: 20px;
                max-width: 600px;
                margin: 20px auto;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #DAA520;
                font-size: 24px;
                margin-bottom: 20px;
            }
            p {
                color: #555;
                font-size: 16px;
                line-height: 1.6;
            }
            .codigo-final {
                font-size: 36px;
                font-weight: bold;
                color: #DAA520;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Confirmación de Reserva</h1>
            <p>Este será el código que presentarás al mozo cuando llegues a la sucursal de Café Sabrosos.</p>
            <p class='codigo-final'>$nuevoCodigoReserva</p>
            <p>Además, hemos adjuntado un código QR para que lo presentes al llegar.</p>
            <div class='footer'>
                <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>";
}

