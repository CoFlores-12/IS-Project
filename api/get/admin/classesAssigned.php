<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['employeenumber'];
$Carrers = $db->execute_query('SELECT section_id, hour_start, class_code, class_name FROM `Section` S
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE employee_number = ?', [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);