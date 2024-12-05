<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['employeenumber'];
$Carrers = $db->execute_query("SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = (
    SELECT student.career_id 
    FROM Students student 
    WHERE student.account_number = r.student_id
)
WHERE r.status IS NULL 
  AND r.request_type_id = 2
  AND p.active = 1
  AND crc.coordinator_id = ?;", [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
$Carrers = $db->execute_query("SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = r.career_change_id
INNER JOIN `Persons` pr ON pr.center_id = crc.center_id
INNER JOIN `Employees` emp ON emp.person_id = pr.person_id
WHERE r.status IS NULL 
  AND r.request_type_id = 3
  AND p.active = 1
  AND crc.coordinator_id = ?
  AND emp.employee_number = ?;", [$id, $id]);

if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
$Carrers = $db->execute_query("SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `Persons` pr ON pr.center_id = r.campus_change_id
INNER JOIN `Employees` emp ON emp.person_id = pr.person_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = (
    SELECT student.career_id 
    FROM Students student 
    WHERE student.account_number = r.student_id
)
WHERE r.status IS NULL 
  AND r.request_type_id = 4
  AND p.active = 1
  AND emp.employee_number = ?
", [$id]);

if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($resultArray);