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

$receiver_id = $idStudent ?? $idEmployee;

$sql = "SELECT cr.request_id, p.first_name, p.last_name, p.personal_email, p.phone
        FROM contact_requests cr
        JOIN Students s ON s.account_number = cr.receiver_id
        JOIN Persons p ON s.person_id = p.person_id
        WHERE cr.receiver_id = ? AND cr.status = 'pending'"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $receiver_id);
$stmt->execute();
$result = $stmt->get_result();

$pendingRequests = [];
while ($row = $result->fetch_assoc()) {
    $pendingRequests[] = $row;
}

echo json_encode($pendingRequests);
?>  