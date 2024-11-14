<?php
include '../../../src/modules/database.php';

header('Content-Type: application/json');

$db = (new Database())->getConnection();
$class_id = $_GET['class_id'];
$response = [];
$response['status'] = true;

session_start();
$id = $_SESSION['studentID'];
$sql = "SELECT * FROM `Enroll` E
INNER JOIN `Section` S
ON E.section_id = S.section_id
WHERE E.student_id = ? AND S.class_id = ?";

$result = $db->execute_query($sql, [$id, $class_id]);
if ($result->num_rows>0) {
    $response['status'] = false;
    $response['message'] = 'Class already registered';
    echo json_encode($response); 
    exit;
}

$Carrers = $db->execute_query("SELECT 
    S.section_id,
    S.hour_start,
    S.days,
    S.quotas,
    P.first_name, 
    P.last_name 
FROM `Section` S
INNER JOIN `Employees` E
ON S.employee_number = `E`.employee_number
INNER JOIN `Persons` P
ON E.person_id = P.person_id 
WHERE class_id = ?", [$class_id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
$response['sections'] = $resultArray;

echo json_encode($response);