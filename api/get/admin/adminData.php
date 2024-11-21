<?php

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Administrator';

AuthMiddleware::checkAccess($requiredRole);
include '../../../src/modules/database.php';

$db = (new Database())->getConnection();
$response = [];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM `Employees` WHERE role_id IN (3,4,5)");
$response['Teachers'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Students");
$response['Students'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Careers");
$response['Careers'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Regional_center");
$response['Regional_center'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.registrationPeriod')) as registrationPeriod
        FROM Config
        WHERE config_id = 1;");
$response['registrationPeriod'] = json_decode($result->fetch_assoc()['registrationPeriod']);

echo json_encode($response);