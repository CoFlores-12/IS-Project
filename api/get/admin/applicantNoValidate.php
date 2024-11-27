<?php
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Validator';

AuthMiddleware::checkAccess($requiredRole);

$employeeNumber = $_SESSION['user']['employeenumber'];


include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$result = $db->execute_query('SELECT 
    A.applicant_id,
    A.person_id,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    TO_BASE64(certify) as certify_base64,
    certify_ext
FROM ValidatorxApplicant va
INNER JOIN `Applicant` A
ON va.applicant_id = A.applicant_id
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
WHERE va.validator_id = ? AND is_valid IS NULL', [$employeeNumber]);

$count = $db->execute_query('SELECT count(*) as rem from `ValidatorxApplicant` WHERE validator_id = ? AND is_valid IS NULL', [$employeeNumber]);

header('Content-Type: application/json');
$response  = $result->fetch_assoc();
$response['rem'] = $count->fetch_assoc()['rem'];
echo json_encode($response);