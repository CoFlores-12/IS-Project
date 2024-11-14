<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

if (!isset($_POST['section_id']) || !is_numeric($_POST['section_id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid section_id'.$_POST['section_id']
    ]);
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

session_start();
$id = $_SESSION['studentID'];
$section_id = $_POST['section_id'];
$status = false;
$message = 'Error in server';

try {
    $result = $conn->execute_query("INSERT INTO Enroll (student_id, section_id, is_waitlist) VALUES (?,?, 0)", [$id, $section_id]);
    if (!$result) {
        new Error("error when registering the class");
    }
    $status = true;
    $message = 'successfully enrolled class';
} catch (\Throwable $th) {
    $message = "error when registering the class".$th;
}

echo json_encode([
    'status'=>$status,
    'message'=> $message
]);