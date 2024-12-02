<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();

$idStudent = $_SESSION['user']['student_id'] ?? null;
$idEmployee = $_SESSION['user']['employeenumber'] ?? null;

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "Sin sesión."
    ]);
    exit;
}

$chatId = $_GET['id'] ?? null;

if (!$chatId) {
    echo json_encode([
        "status" => false,
        "error" => "Chat ID es requerido."
    ]);
    exit;
}

$participantCheck = $db->execute_query("
    SELECT cp.chat_id
    FROM ChatParticipants cp
    INNER JOIN Persons p ON cp.person_id = p.person_id
    WHERE cp.chat_id = ? AND (
        p.person_id = (SELECT person_id FROM `Students` WHERE account_number = ?)
        OR 
        p.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = ?)
    )
", [$chatId, $idStudent, $idEmployee]);

if (!$participantCheck->fetch_assoc()) {
    echo json_encode([
        "status" => false,
        "message" => "Sin autorización para ver este chat"
    ]);
    exit;
}
function decryptMessage($encryptedMessage, $secretKey) {
    @define('ENCRYPTION_METHOD', 'AES-256-CBC');
    @list($encryptedData, $iv) = explode('::', base64_decode($encryptedMessage), 2);
    return @openssl_decrypt($encryptedData, ENCRYPTION_METHOD, $secretKey, 0, $iv);
}


$messagesResult = $db->execute_query("
   SELECT 
    m.message_id,
    m.sender_id,
    m.chat_id,
    m.content,
    m.status,
    C.secret,
    IF(
        m.sender_id = (
            SELECT person_id FROM Students WHERE account_number = ?
        ) OR m.sender_id = (
            SELECT person_id FROM Employees WHERE employee_number = ?
        ), 
        'me', 
        'other'
    ) AS sender_type,
    CONVERT_TZ(m.sent_at, '+00:00', '-06:00') AS sent_at_adjusted,
    p.first_name,
    p.last_name
FROM 
    Messages m
INNER JOIN 
    Persons p ON m.sender_id = p.person_id
INNER JOIN 
    Chats C ON m.chat_id = C.chat_id 
WHERE 
    m.chat_id = ?
ORDER BY 
    m.sent_at ASC;

", [$idStudent,$idEmployee,$chatId]);

$messages = [];
if ($messagesResult) {
    while ($row = $messagesResult->fetch_assoc()) {
        if (!empty($row['content']) && !empty($row['secret'])) {
            $row['content'] = decryptMessage($row['content'], $row['secret']);
        }
        $row['secret'] = '';
        $messages[] = $row;
    }
}

$result = $db->execute_query("SELECT 
    c.is_group,
    COALESCE(cg.group_name, CONCAT(p.first_name, ' ', p.last_name)) AS chat_name,
    MAX(la.DATE) AS last_connection
FROM Chats c
LEFT JOIN ChatsGroups cg ON c.group_id = cg.group_id
LEFT JOIN ChatParticipants cp ON c.chat_id = cp.chat_id
LEFT JOIN Persons p ON cp.person_id = p.person_id
LEFT JOIN LogAuth la ON p.person_id = la.identifier
WHERE c.chat_id = ? 
  AND (c.is_group = 1 OR (
        p.person_id = (SELECT person_id FROM `Students` WHERE account_number = ?)
        OR 
        p.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = ?)
    ))
GROUP BY c.chat_id, c.is_group, chat_name;
", [$chatId, $idStudent, $idEmployee]);
$row = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode([
    "status" => true,
    "data" => $messages,
    "info" => $row
]);