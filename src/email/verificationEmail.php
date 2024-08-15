<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Cargar el autoload de Composer

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'marktecs.a.s@gmail.com'; // Tu correo SMTP
        $mail->Password = 'xwrvyezxmwzqvqgb'; // Tu contraseña SMTP
        $mail->SMTPSecure = 'tls'; // Habilitar encriptación TLS
        $mail->Port = 587; // Puerto para TLS

        // Remitente y destinatario
        $mail->setFrom('no-reply@cafesabrosos.myvnc.com', 'Tu Empresa');
        $mail->addAddress($to);

        // Contenido del correo
        $mail->isHTML(true); // Si quieres enviar HTML, cámbialo a true
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Registrar el error
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
