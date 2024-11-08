<?php
header('Content-Type: application/json');

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
 }

session_start();
$role = $_SESSION['role'];

if ($role != 'Department Head') {
    http_response_code(404);
    echo json_encode(['Message' => 'You do not have privileges to do this action']);
    return;
}

if (!isset($_GET['teacher_identifier'])) {
    http_response_code(404);
    echo json_encode(['Message' => 'Incorrect request, missing teacher ID']);
    return;
}

$teacher_identifier = $_GET['teacher_identifier'];

$email = $_GET['email'];

include '../../../src/modules/mails.php';
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

function generatePassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}

$newPassword = generatePassword();

$passphrase = getenv('password');

$sql = '
    UPDATE Employees 
    SET password = AES_ENCRYPT(?, ?) 
    WHERE employee_number = ?;
';

$result = $conn->execute_query($sql, [ $newPassword, $passphrase, $teacher_identifier]);

if ($result) {
    if ($conn->affected_rows > 0) {
        echo json_encode(['Message' => 'Password updated successfully']);
        $affair = "Actualizacion de contraseña";
        $message = "Tu Contraseña del sistema es: <strong>$newPassword</strong>";

        $resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);
    } else {
        http_response_code(404);
        echo json_encode(['Message' => 'No data found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['Message' => 'Error in the query']);
}


$conn->close();
exit;