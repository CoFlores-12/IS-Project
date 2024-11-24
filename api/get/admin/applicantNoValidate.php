<?php
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Validator';

AuthMiddleware::checkAccess($requiredRole);
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$result = $db->execute_query('SELECT 
    A.applicant_id,
    A.person_id,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    TO_BASE64(certify) as certify_base64,
    certify_ext
from `Applicant` A
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
WHERE status_id = 0 AND validated IS NULL');

$count = $db->execute_query('SELECT count(*) as rem from `Applicant` WHERE status_id = 0 AND validated IS NULL');

header('Content-Type: application/json');
$response  = $result->fetch_assoc();
$response['rem'] = $count->fetch_assoc()['rem'];
echo json_encode($response);