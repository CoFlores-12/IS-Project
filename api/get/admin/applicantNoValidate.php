<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$result = $db->execute_query('SELECT 
    A.applicant_id,
    A.person_id,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    A.certify
from `Applicant` A
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
WHERE status_id = 0 AND validated  IS NULL');

header('Content-Type: application/json');
echo json_encode($result->fetch_assoc());