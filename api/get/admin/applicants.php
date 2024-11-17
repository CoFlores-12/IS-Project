<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$result = $db->execute_query("SELECT E.exam_code, EC.career_id, E.exam_name FROM `ExamsXCareer` EC
INNER JOIN `Exams` E ON EC.exam_code = E.exam_code");

$response = [];

$Exams = [];
while ($row = $result->fetch_assoc()) {
    $Exams[] = $row;  
}
$response['Exams'] = $Exams;

$result = $db->execute_query('SELECT 
    A.person_id as identity,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    A.preferend_career_id,
    C.career_name as preferend_career_name,
    A.secondary_career_id,
    CS.career_name as secondary_career_name
from `Applicant` A
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
INNER JOIN `Careers` C
ON A.preferend_career_id = C.career_id
INNER JOIN `Careers` CS
ON A.secondary_career_id = CS.career_id
 WHERE status_id = 0');
$Asp = [];
while ($row = $result->fetch_assoc()) {
    $Asp[] = $row;  
}
$response['Asp'] = $Asp;



header('Content-Type: application/json');
echo json_encode($response);