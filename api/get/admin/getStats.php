<?php

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);
include '../../../src/modules/database.php';
$id = $_SESSION['user']['employeenumber'];
$db = (new Database())->getConnection();
$response = [];

$result = $db->execute_query("SELECT COUNT(*) as count FROM `Students` s
INNER JOIN `Careers` c ON s.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
)",[$id]);
$response['students'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) as count FROM `Employees`
WHERE department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
);",[$id]);
$response['employees'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) as count FROM
`ClassesXCareer` cl 
INNER JOIN `Careers` c ON cl.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
);",[$id]);
$response['classes'] = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT 
    SUM(CASE WHEN h.obs_id = 1 THEN 1 ELSE 0 END) AS APB,
    SUM(CASE WHEN h.obs_id = 0 THEN 1 ELSE 0 END) AS RPB,
    SUM(CASE WHEN h.obs_id = 2 THEN 1 ELSE 0 END) AS ABD,
    SUM(CASE WHEN h.obs_id = 3 THEN 1 ELSE 0 END) AS NSP
FROM 
    History h
JOIN 
    Section s ON h.section_id = s.section_id
JOIN `ClassesXCareer` cxc ON s.class_id = cxc.class_id
INNER JOIN `Careers` c ON cxc.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
);",[$id]);
$response['tasa'] = $result->fetch_assoc();

$result = $db->execute_query("SELECT 
    CONCAT(pr.indicator, ' PAC ', pr.year) as period,
    AVG(h.score) AS average_score
FROM 
    History h
JOIN 
    Section s ON h.section_id = s.section_id
JOIN 
    `ClassesXCareer` cxc ON s.class_id = cxc.class_id
INNER JOIN 
    `Careers` c ON cxc.career_id = c.career_id
JOIN
    Periods pr ON s.period_id = pr.period_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = ?
)
GROUP BY 
    s.period_id
LIMIT 9;",[$id]);

$resultArray = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
$response['avg'] = $resultArray;

echo json_encode($response);