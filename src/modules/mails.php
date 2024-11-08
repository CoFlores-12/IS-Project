<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

class Mails {
    private $mail;

    /**
     * Builder that configures PHPMailer to send mail using SMTP.
     * Load sender credentials and SMTP configuration from a .env file.
     */
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

    /**
     * Send an email to a specific recipient.
     *
     * @param string $from Sender's email address.
     * @param string $to Email address of the recipient.
     * @param string $subject Email subject.
     * @param string $body Message body in HTML format.
     * @return string 
     */
    public function sendEmail($to, $email, $affair, $message) {
        try {
            
            $this->mail->setFrom($to);
            $this->mail->addAddress($email); 
            
            $this->mail->isHTML(true);
            $this->mail->Subject = $affair;
            $this->mail->Body = $message;
            $this->mail->AltBody = strip_tags($message); 

           
            $this->mail->send();
            return "Mail sent successfully.";
        } catch (Exception $e) {
            return "Error sending email: {$this->mail->ErrorInfo}";
        }
    }
}
