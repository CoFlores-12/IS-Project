<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$class_id = $_GET['class_id'];
$Carrers = $db->execute_query("SELECT 
    S.section_id,
    S.hour_start,
    S.days,
    S.quotas,
    P.first_name, 
    P.last_name 
FROM `Section` S
INNER JOIN `Employees` E
ON S.employee_number = `E`.employee_number
INNER JOIN `Persons` P
ON E.person_id = P.person_id 
WHERE class_id = ?", [$class_id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);