<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..'); // Ruta al directorio raíz del proyecto
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Filtrar y sanitizar los datos del formulario
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Verificar si todos los campos requeridos están presentes y son válidos
    if (!$name || !$email || !$subject || !$message) {
        echo "Por favor, completa todos los campos del formulario.";
        exit;
    }
    // Envío de correo electrónico

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTPEMAIL']; // Tu correo electrónico SMTP
        $mail->Password = $_ENV['SMTPPASS']; // Tu contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom($email, $name); // Reemplaza con tu correo de Gmail y nombre del remitente
        $mail->addAddress($_ENV['SMTPEMAIL']); // Reemplaza con tu correo de destino

        $mail->isHTML(true);
        $mail->Subject = $subject;

        // Construir el cuerpo del mensaje
        $messageBody = "<h2>Nuevo mensaje del formulario de contacto</h2>
                        <p><strong>Nombre:</strong> {$name}</p>
                        <p><strong>Email:</strong> {$email}</p>
                        <p><strong>Asunto:</strong> {$subject}</p>
                        <p><strong>Mensaje:</strong> {$message}</p>";

        

        $messageBody .= "<hr><p>Este mensaje fue enviado desde la dirección IP: {$_SERVER['REMOTE_ADDR']}</p>";

        $mail->Body = $messageBody;

        $mail->send();
        echo 'El mensaje se ha enviado correctamente.';

       
    } catch (Exception $e) {
        echo 'Hubo un error al enviar el mensaje: ' . $e->getMessage();
    }
}
?>