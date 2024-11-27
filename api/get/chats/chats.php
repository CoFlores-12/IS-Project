<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$idStudent = null;
$idEmployee = null;

if (isset($_SESSION['user']['student_id'])) {
    $idStudent = $_SESSION['user']['student_id'];
}
if (isset($_SESSION['user']['employeenumber'])) {
    $idEmployee = $_SESSION['user']['employeenumber'];
}

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "No valid identifier found in session."
    ]);
    exit;
}

$result = $db->execute_query("
WITH LastMessage AS (
    SELECT
        chat_id,
        MAX(sent_at) AS last_message_time
    FROM Messages
    GROUP BY chat_id
),
MessageDetails AS (
    SELECT
        m.chat_id,
        m.content AS last_message,
        m.sent_at AS message_time
    FROM Messages m
    INNER JOIN LastMessage lm ON m.chat_id = lm.chat_id AND m.sent_at = lm.last_message_time
)
SELECT
    c.chat_id,
    c.is_group,
    -- Datos para grupos
    g.group_name AS group_name,
    g.group_photo AS group_photo,
    -- Datos para chats directos
    CONCAT(p.first_name, ' ', p.last_name) AS direct_user_name,
    -- Último mensaje
    md.last_message,
    md.message_time
FROM Chats c
LEFT JOIN ChatsGroups g ON c.group_id = g.group_id
LEFT JOIN ChatParticipants cp ON c.chat_id = cp.chat_id
LEFT JOIN Persons p ON p.person_id = (
    SELECT person_id
    FROM ChatParticipants
    WHERE 
        (chat_id = c.chat_id AND person_id != (SELECT person_id FROM `Students` WHERE account_number = ?))
    OR 
        (chat_id = c.chat_id AND person_id != (SELECT person_id FROM `Employees` WHERE employee_number = ?))
    LIMIT 1
)
LEFT JOIN MessageDetails md ON c.chat_id = md.chat_id
WHERE 
    cp.person_id = (SELECT person_id FROM `Students` WHERE account_number = ?)
OR
    cp.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = ?)
ORDER BY md.message_time DESC;

", [$idStudent,$idEmployee, $idStudent, $idEmployee]);

$resultArray = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    "status"=>true,
    "data"=>$resultArray
]);