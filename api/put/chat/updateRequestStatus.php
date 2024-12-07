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

$request_id = $_POST['request_id']; 
$status = $_POST['status']; 

$sql = "UPDATE contact_requests SET status = ? WHERE request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $request_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Solicitud actualizada correctamente"]);
} else {
    echo json_encode(["error" => "Error al actualizar la solicitud"]);
}


echo json_encode($pendingRequests);
?>  