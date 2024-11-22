<?php
header('Content-Type: application/json');

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
 }

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);

$data = json_decode(file_get_contents('php://input'), true);

$employeeId = $data['teacher_identifier'];
$email = $data['personal_email'];

include '../../../src/modules/mails.php';
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

function generatePasswordResetToken() {
    $token = bin2hex(random_bytes(8));
    
    return $token;
   
}

$newToken = generatePasswordResetToken();
$create = date("Y-m-d H:i:s");
$expiry = date("Y-m-d H:i:s", strtotime("+2 minutes"));


$sql = "INSERT INTO PasswordResetTokens (identifier, token, created_at, expires_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $employeeId, $newToken,$create ,$expiry);
$stmt->execute();

$response = [];

$resetUrl = 'https://is-project-fixes.up.railway.app/views/admin/teacher/home/reset.php?token='.urlencode($newToken);

$affair = "Cambiar clave";
$message = sprintf('
 <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #ffffff; border: 1px solid #dddddd;">
     <tr>
         <td align="center" style="padding: 20px; background-color: #007bff; color: #ffffff; font-size: 24px; font-weight: bold;">
             Notificacion de cambio de clave
         </td>
     </tr>
     <tr>
         <td style="padding: 20px; color: #333333; font-size: 16px; line-height: 1.6;">
             <p>Estimado condente,</p>
             <p>El presente es respondiendo a su peticion de cambio de contrasenia. Por favor, dirijase al siguiente enlace:</p>
             <p style="text-align: center; margin: 20px 0;">
                 <a href="%s" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; display: inline-block;">Cambiar clave</a>
             </p>
             <p>Si no solicito esto, ignore este correo o comuniquese con el soporte si tiene inquietudes.</p>
             <p>Nota: Este enlace caducara en 2 minutos.</p>
         </td>
     </tr>
     <tr>
         <td style="padding: 20px; background-color: #f4f4f4; color: #555555; font-size: 14px; text-align: center;">
             <p>Si tiene alguna pregunta, comuníquese con nuestro equipo de soporte en <a href="mailto:support@unah.com" style="color: #007bff;">support@yourwebsite.com</a>.</p>
             <p>&copy; 2024 UNAH. Todos los derechos reservados.</p>
         </td>
     </tr>
 </table>', $resetUrl);
    
$resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);


if ($stmt->affected_rows > 0) {
    $response = ["status" => 0];
} else {
    $response = ["status" => 1];
}

echo json_encode($response);

$conn->close();
exit;