<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../auth/verifyToken.php';
checkToken();
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function sendContactEmail($to, $subject, $body): bool
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL']; // Usuario SMTP
        $mail->Password = $_ENV['SMTPPASS']; // Contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cifrado TLS
        $mail->Port = 587; // Puerto TCP

        // Receptores
        $mail->setFrom('no-reply@cafesabrosos.myvnc.com', 'Café Sabrosos');
        $mail->addAddress($to); // Añadir destinatario

        // Contenido
        $mail->isHTML(true); // Formato HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo 'El mensaje se ha enviado correctamente.';
        return true;
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
        return false;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $body = "
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
            <h1>Nuevo mensaje de contacto</h1>
            <p><strong>Nombre:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Asunto:</strong> $subject</p>
            <p><strong>Mensaje:</strong></p>
            <p>$message</p>
            <div class='footer'>
                <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>";

    sendContactEmail($_ENV['SMTPEMAIL'], $subject, $body);
}
?>