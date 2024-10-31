<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './../../../vendor/autoload.php';

class Mails {
    private $mail;

    public function __construct($user, $password) {
        $this->mail = new PHPMailer(true);
        
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $user; 
        $this->mail->Password = $password;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
    }

    public function sendEmail($de, $mailAspirant, $affair, $message) {
        try {
            
            $this->mail->setFrom($de, 'Sistema de Admisiones');
            $this->mail->addAddress($mailAspirant); 
            
            $this->mail->isHTML(true);
            $this->mail->Subject = $affair;
            $this->mail->Body = $message;
            $this->mail->AltBody = strip_tags($message); 

           
            $this->mail->send();
            return "Correo enviado correctamente.";
        } catch (Exception $e) {
            return "Error al enviar el correo: {$this->mail->ErrorInfo}";
        }
    }
}
