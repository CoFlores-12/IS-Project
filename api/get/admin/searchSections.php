<?php
header('Content-Type: application/json');

session_start();
$employeetid = $_SESSION['departmentid'];
$role = $_SESSION['role'];

if ($role != 'Department Head') {
    http_response_code(404);
    echo json_encode(['Message' => 'You do not have privileges to do this action']);
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql ='
            SELECT 
            s.section_id,
            c.class_name,
            b.building_name,
            s.hour_start,
            s.hour_end,
            s.period_id,
            s.classroom_id,
            cr.classroom_name,
            s.quotas,
            sd.Monday,
            sd.Tuesday,
            sd.Wednesday,
            sd.Thursday,
            sd.Friday,
            sd.Saturday,
            (SELECT COUNT(*) 
            FROM Enroll e
            WHERE e.section_id = s.section_id) AS enrolled_students
        FROM 
            Section s
        INNER JOIN 
            SectionDays sd ON s.section_id = sd.section_id
        INNER JOIN 
            Employees e_section ON s.employee_number = e_section.employee_number
        INNER JOIN 
            Classes c ON s.class_id = c.class_id
        INNER JOIN 
            Classroom cr ON s.classroom_id = cr.classroom_id
        INNER JOIN 
            Building b ON cr.building_id = b.building_id
        WHERE 
            e_section.department_id = (
                SELECT department_id
                FROM Employees e
                WHERE e.employee_number = ?);
        ';

try {
    $result = $conn->execute_query($sql, [$employeetid]);
    $data = $result->fetch_all(MYSQLI_ASSOC); 
    
    if ($data) {
        echo json_encode($data); 
    } else {
        http_response_code(404); 
        echo json_encode(['Message' => 'No sections found for the specified employee.']);
    }
} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode(['Message' => 'Error executing the query.', 'Error' => $e->getMessage()]);
}


$conn->close();
exit;