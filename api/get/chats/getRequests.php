<?php
header("Content-Type: application/json");
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();
session_start();

$idStudent = $_SESSION['user']['student_id'] ?? null;
$idEmployee = $_SESSION['user']['employeenumber'] ?? null;

$response = [];

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "No valid identifier found in session."
    ]);
    exit;
}

$sender_id = $idStudent ?? $idEmployee;

$sql = "SELECT p.first_name, p.last_name, s.account_number
FROM contact_requests cr
JOIN Students s ON s.account_number = cr.sender_id
JOIN Persons p ON p.person_id = s.person_id
WHERE sender_id = ? AND status = 'pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$pendingRequests = [];
while ($row = $result->fetch_assoc()) {
    $pendingRequests[] = $row;
}

echo json_encode($pendingRequests);
?>