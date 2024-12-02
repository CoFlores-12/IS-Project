<?php
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();
session_start();

$idStudent = $_SESSION['user']['student_id'] ?? null;
$idEmployee = $_SESSION['user']['employeenumber'] ?? null;

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "No valid identifier found in session."
    ]);
    exit;
}

$chatID = $_POST['chatID'];
$message_plain = $_POST['message'];
$result = $conn->execute_query('SELECT secret FROM Chats where chat_id = ?', [$chatID]);
$row = $result->fetch_assoc();

$result = $conn->execute_query("SELECT p.person_id FROM `Persons` p
WHERE p.person_id = (SELECT person_id FROM `Students` WHERE account_number = ?)
    OR 
    p.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = ?)", [$idStudent, $idEmployee]);
$row2 = $result->fetch_assoc();
if (!isset($row2['person_id'])) {
    echo json_encode(["status"=> false]);
    exit;
}
$id = $row2['person_id'];

define('ENCRYPTION_KEY', $row['secret']); 
define('ENCRYPTION_METHOD', 'AES-256-CBC');

function encryptMessage($message) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($message, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}


$encryptedContent = encryptMessage($message_plain);

$sql = "INSERT INTO Messages (sender_id, chat_id, content) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $id, $chatID, $encryptedContent);
$stmt->execute();

echo json_encode(["status"=> true]);
exit;