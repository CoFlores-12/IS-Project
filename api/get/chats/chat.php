<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
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

$chatId = $_GET['id'] ?? null;

if (!$chatId) {
    echo json_encode([
        "status" => false,
        "error" => "Chat ID is required."
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
        "error" => "You are not authorized to view this chat."
    ]);
    exit;
}

$messagesResult = $db->execute_query("
    SELECT 
        *
    FROM Messages m
    INNER JOIN Persons p ON m.sender_id = p.person_id
    WHERE m.chat_id = ?
    ORDER BY m.sent_at ASC
", [$chatId]);

$messages = [];
if ($messagesResult) {
    while ($row = $messagesResult->fetch_assoc()) {
        $messages[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    "status" => true,
    "data" => $messages
]);