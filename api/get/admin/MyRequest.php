<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
//$id = $_SESSION['user']['student_id'];
$Carrers = $db->execute_query("SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) as period
FROM  `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p on r.period_id = p.period_id
where r.status IS NULL AND r.request_type_id != 1");

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($resultArray);