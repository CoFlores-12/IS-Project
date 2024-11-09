<?php
header('Content-Type: application/json');

session_start();
$role = $_SESSION['role'];
$departmentid = $_SESSION['departmentid'];

if ($role != 'Department Head') {
    http_response_code(404);
    echo json_encode(['Message' => 'You do not have privileges to do this action']);
    return;
}



include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();


echo "<script>console.log('" . addslashes("asd") . "');</script>";













$sql = '
';

$result = $conn->execute_query($sql, [$teacher_identifier, $teacher_identifier, $teacher_identifier]);

if ($result) {
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['Message' => 'No data found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['Message' => 'Error in the query']);
}


$conn->close();
exit;