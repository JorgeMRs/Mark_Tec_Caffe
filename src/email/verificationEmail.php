<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// funcion para enviar el correo de verificación

function sendEmail($to, $subject, $body): bool
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL']; // Tu correo SMTP
        $mail->Password = $_ENV['SMTPPASS']; // Tu contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar encriptación TLS
        $mail->Port = 587; // Puerto para TLS

        // Remitente y destinatario
        $mail->setFrom('cafesabrosos@gmail.com', 'Cafe Sabrosos S.A.S');
        $mail->addAddress($to);

        // Contenido del correo
        $mail->isHTML(); // Si quieres enviar HTML, cámbialo a true
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception) {
        // Registrar el error
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

function getVerificationEmailBody($verificationLink): string
{
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
            .container {
                background-color: #ffffff;
                border-radius: 8px;
                padding: 20px;
                max-width: 600px;
                margin: 20px auto;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #B9860A;
                font-size: 24px;
                margin-bottom: 20px;
            }
            p {
                color: #555;
                font-size: 16px;
                line-height: 1.6;
            }
            a {
                color: #B9860A;
                text-decoration: none;
                font-weight: bold;
            }
            a:hover {
                text-decoration: underline;
            }
            .footer {
                color: #888;
                text-align: center;
                font-size: 14px;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Hola,</h1>
            <p>Gracias por registrarte en Cafe Sabrosos. Por favor, verifica tu cuenta haciendo clic en el siguiente enlace:</p>
            <p><a href='$verificationLink'>Verificar mi cuenta</a></p>
            <p>Si no solicitaste esta cuenta, ignora este correo.</p>
            <div class='footer'>
                <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>";
}
