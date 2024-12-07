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

$input = json_decode(file_get_contents("php://input"), true);

$receiver_id = $input['receiver_id'];


$sql = "SELECT * FROM contact_requests WHERE sender_id = ? AND receiver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response[] = ["estatus" => false,  "error" => "solicitud ya enviada"];
    } else {
        $sql = "INSERT INTO contact_requests (sender_id, receiver_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $sender_id, $receiver_id);

        if ($stmt->execute()) {
            $response[] = ["estatus" => true,  "error" => "solicitud enviada"];
        } else {
            $response[] = ["estatus" => false,  "error" => "algo salio mal"];
        }
    }

echo json_encode([$response]);
exit;