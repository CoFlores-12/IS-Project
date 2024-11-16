<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['studentID'];
$Carrers = $db->execute_query('SELECT E.enroll_id, S.hour_start, CONCAT(C.class_code, " ", C.class_name) as class_name, is_waitlist  FROM `Enroll` E
INNER JOIN `Section` S
ON E.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE E.student_id = ?', [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);