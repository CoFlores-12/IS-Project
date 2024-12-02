<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['student_id'];
$Carrers = $db->execute_query("SELECT 
    r.status,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    r.response,
    rt.title
FROM  `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p on r.period_id = p.period_id
WHERE p.active = 1 AND  r.student_id = ?", [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($resultArray);