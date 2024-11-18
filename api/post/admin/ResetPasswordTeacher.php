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
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));


$sql = "INSERT INTO PasswordResetTokens (employee_id, token, created_at, expires_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isss', $employeeId, $newToken,$create ,$expiry);
$stmt->execute();

$response = [];

$resetUrl = 'https://is-project.up.railway.app/views/admin/teacher/home/reset.php?token='.urlencode($newToken);

$affair = "Cambiar contraseña";
$message = "Ve al siguiente enlace para cambiar tu contrasenia: <a href='${resetUrl}'>aqui</a>";
    
$resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);


if ($stmt->affected_rows > 0) {
    $response = ["status" => 0];
} else {
    $response = ["status" => 1];
}

echo json_encode($response);

$conn->close();
exit;