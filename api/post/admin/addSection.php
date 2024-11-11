<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
    exit();
} 

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$classId = $data['classId'] ?? null;
$starttime = $data['starttime'] ?? null;
$endtime = $data['endtime'] ?? null;
$classroomId = $data['classroomId'] ?? null;

/*
$teacherId = $_POST['teacherId'];
$quotas = $_POST['quotas'];*/

if (!empty($classId) && !empty($starttime) && !empty($endtime) && !empty($classroomId)) {
    $sql = "INSERT INTO Section (class_id, hour_start, hour_end, classroom_id) VALUES (?, ?, ?, ?)";
    $conn->execute_query($sql, [$classId, $starttime, $endtime, $classroomId]);
    $conn->close();
    echo json_encode(["success" => true, "message" => "Saved correctly."]);
} else {
    echo json_encode(["success" => false, "message" => "Insufficient data."]);
}
?>