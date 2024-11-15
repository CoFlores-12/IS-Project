<?php

//get data from form
parse_str(file_get_contents("php://input"), $data);

$enrolled_id = isset($data['enrolled_id']) ? $data['enrolled_id'] : null;

//validate data
if (!$enrolled_id) {
    http_response_code(400);
    echo json_encode(["message" => "Missing parameters: enrolled_id are required."]);
    return;
}

session_start();
$id = $_SESSION['studentID'];

//conneted to DB
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$status = false;
$message = 'Error in server';

try {
    //get section_id
    $result = $conn->execute_query("SELECT section_id, is_waitlist FROM Enroll WHERE enroll_id = ? AND student_id = ?", [$enrolled_id, $id]);
    $row = $result->fetch_assoc();
    $section_id = $row['section_id'];
    $is_waitlist = $row['is_waitlist'];
    
    //cancel class
    $result = $conn->execute_query("DELETE FROM `Enroll` WHERE enroll_id = ? AND student_id = ?", [$enrolled_id, $id]);
    
    //save store 
    $result = $conn->execute_query("INSERT INTO `ClassesCanceled` VALUES (?,?)", [$section_id, $id]);
    
    //assign quotas
    if ($is_waitlist == 0) {
        $result = $conn->execute_query("SELECT enroll_id FROM Enroll WHERE section_id = ? AND is_waitlist = 1 LIMIT 1", [$section_id]);
        $row = $result->fetch_assoc();
        if (isset($row['enroll_id'])) {
            $enroll_id = $row['enroll_id'];
            $result = $conn->execute_query("UPDATE Enroll SET is_waitlist = 0 WHERE enroll_id = ?", [$enroll_id]);
        }
    }
    
    $status = true;
    $message = 'successfully canceled class';
} catch (\Throwable $th) {
    $message = "error when canceling the class".$th;
}

echo json_encode([
    'status'=>$status,
    'message'=> $message
]);


?>
