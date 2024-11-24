<?php
header('Content-Type: application/json');

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Coordinator';

AuthMiddleware::checkAccess($requiredRole);

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "SELECT  
    R.student_id, 
    CONCAT(P.first_name, ' ', P.last_name) as student_name, 
    R.`date`, 
    R.comments 
FROM `Requests` R
INNER JOIN `Students` S
ON R.student_id = S.account_number
INNER JOIN `Persons` P
ON S.person_id = P.person_id
WHERE R.request_type_id = 3;";

$result = $conn->execute_query($sql);

if ($result) {
    $array = array();
    while ($row = $result->fetch_assoc()) {
        $array[] = $row;
    }
    echo json_encode($array);
} else {
    http_response_code(404);
    echo json_encode(['Message' => 'No data found']);
}

$conn->close();
exit;