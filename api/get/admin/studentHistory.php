<?php
header('Content-Type: application/json');

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);

if (!isset($_GET['student_identifier'])) {
    http_response_code(404);
    echo json_encode(['Message' => 'Bad request, missing student identifier']);
    return;
}

$student_identifier = $_GET['student_identifier'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = 'SELECT C.class_code, C.class_name, A.score FROM History A
INNER JOIN Students B
ON A.student_id = B.account_number
INNER JOIN `Section` S
ON A.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE B.account_number = ? OR B.person_id = ? OR B.institute_email = ?
';

$result = $conn->execute_query($sql, [$student_identifier, $student_identifier, $student_identifier]);

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