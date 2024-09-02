<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust the path as necessary

function sendPasswordResetEmail($to, $subject, $body): bool
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL']; // SMTP username
        $mail->Password = $_ENV['SMTPPASS']; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@cafesabrosos.myvnc.com', 'Café Sabrosos');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log error
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

function getPasswordResetEmailBody($resetLink): string
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
            <h1>Solicitud de Restablecimiento de Contraseña</h1>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Por favor, haz clic en el siguiente enlace para crear una nueva contraseña:</p>
            <p><a href='$resetLink'>Restablecer mi contraseña</a></p>
            <p>Si no solicitaste este restablecimiento de contraseña, ignora este correo.</p>
            <div class='footer'>
                <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>";
}
?>
