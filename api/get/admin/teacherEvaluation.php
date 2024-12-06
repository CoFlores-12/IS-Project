<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

session_start();
$id = $_SESSION['user']['employeenumber'];

$query = "
   SELECT 
        ste.evaluation_id,
        ste.student_account_number,
        CONCAT(p.first_name, ' ', p.last_name) AS teacher_name,
        ste.responses,
        emp.employee_number,
        ste.created_at,
        se.section_id,
        COALESCE(e.score, h.score) AS student_score 
    FROM student_teacher_evaluation ste
    JOIN Employees emp ON ste.teacher_id = emp.employee_number
    JOIN Persons p ON emp.person_id = p.person_id
    JOIN Section se ON ste.section_id = se.section_id
    LEFT JOIN Enroll e ON e.student_id = ste.student_account_number AND e.section_id = se.section_id
    LEFT JOIN History h ON h.student_id = ste.student_account_number AND h.section_id = se.section_id
    WHERE emp.department_id = (
        SELECT department_id
        FROM Employees
        WHERE employee_number = ? 
    )
    ORDER BY ste.created_at DESC;
";

$stmt = $db->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$evaluations = [];
while ($row = $result->fetch_assoc()) {
    $evaluations[] = $row;
}

echo json_encode($evaluations);
?>