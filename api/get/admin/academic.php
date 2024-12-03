<?php

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Coordinator';

AuthMiddleware::checkAccess($requiredRole);
$employeeNumber = $_SESSION['user']['employeenumber'];
include '../../../src/modules/database.php';

$db = (new Database())->getConnection();

$result = $db->execute_query("SELECT 
    s.section_id,
    c.class_code,
    c.class_name,
    e.employee_number,
    CONCAT(p.first_name, ' ', p.last_name) as teacher,
    (
        SELECT COUNT(*) FROM `Enroll` en
        WHERE en.section_id = s.section_id
    ) as enrolled,
    s.quotas,
    cr.classroom_name,
    b.building_name
FROM `Section` s
INNER JOIN `Classes` c 
ON s.class_id = c.class_id
INNER JOIN `Employees` e 
ON s.employee_number = e.employee_number
INNER JOIN `Persons` p 
ON e.person_id = p.person_id
INNER JOIN `Classroom` cr 
ON s.classroom_id = cr.classroom_id
INNER JOIN `Building` b 
ON cr.building_id = b.building_id
INNER JOIN `Periods` pe
ON s.period_id = pe.period_id
INNER JOIN `ClassesXCareer` cxc 
ON c.class_id = cxc.class_id
INNER JOIN `Careers` ca
ON cxc.career_id = ca.career_id
WHERE pe.active = 1 AND ca.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
)", [$employeeNumber]);

$resultArray = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

echo json_encode($resultArray);