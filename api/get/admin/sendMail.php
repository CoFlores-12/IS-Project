<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './../../../vendor/autoload.php';

function enviarCorreoMicrosoft365($de, $para, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Microsoft 365
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = '@unah.hn'; // Tu correo institucional
        $mail->Password = ''; // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom($de, 'Nombre del Remitente');
        $mail->addAddress($para); // Destinatario

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = strip_tags($mensaje); // Mensaje en texto plano

        // Enviar el correo
        $mail->send();
        return "Correo enviado correctamente.";
    } catch (Exception $e) {
        return "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}

// Uso del ejemplo
$de = "cofloresf@unah.hn";
$para = "obethflores2014@gmail.com";
$asunto = "Prueba de correo SMTP con Microsoft 365";
$mensaje = "<h1>Este es un mensaje de prueba</h1><p>Enviado desde una cuenta institucional de Microsoft 365 usando PHPMailer.</p>";

echo enviarCorreoMicrosoft365($de, $para, $asunto, $mensaje);
?>
