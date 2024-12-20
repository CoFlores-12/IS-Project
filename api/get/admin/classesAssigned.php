<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['employeenumber'];
$Carrers = $db->execute_query('SELECT S.section_id, S.hour_start, C.class_code, C.class_name  
FROM  `Section` S
INNER JOIN `Classes` C
ON S.class_id = C.class_id
INNER JOIN Periods P
ON S.period_id = P.period_id
WHERE S.employee_number = ? AND P.active = 1 ', [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);