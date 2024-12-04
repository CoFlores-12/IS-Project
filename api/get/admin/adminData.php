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

$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.EnrollPeriod')) as EnrollPeriod
        FROM Config
        WHERE config_id = 1;");
$response['EnrollPeriod'] = json_decode($result->fetch_assoc()['EnrollPeriod']);

$result = $db->execute_query("SELECT log_id, 
       CONVERT_TZ(DATE, '+00:00', '-06:00') AS local_time, 
       ip_address, 
       auth_status, 
       R.role_id, 
       identifier,
       R.type
FROM LogAuth L left join Roles R on L.role_id = R.role_id");

$resultArray = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
$response['logs'] = $resultArray;

$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.cancellationExceptional')) AS cancellationExceptional 
          FROM Config 
          WHERE config_id = 1;");
$response['cancellationExceptional'] = json_decode($result->fetch_assoc()['cancellationExceptional']);

$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.uploadNotes')) AS uploadNotes 
          FROM Config 
          WHERE config_id = 1;");
$response['uploadNotes'] = json_decode($result->fetch_assoc()['uploadNotes']);

echo json_encode($response);