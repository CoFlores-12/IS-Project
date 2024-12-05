<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

if (!isset($_GET['departmentId']) || empty($_GET['departmentId'])) {
    echo json_encode(['error' => 'El parámetro departmentId es obligatorio.']);
    exit;
}

$departmentId = intval($_GET['departmentId']);

// Consulta SQL
$sql = "
    SELECT 
        Students.account_number AS `Account Number`,
        Persons.first_name AS `First Name`,
        Persons.last_name AS `Last Name`,
        GROUP_CONCAT(DISTINCT Classes.class_name SEPARATOR ', ') AS `Enrolled Classes`
    FROM Students
    JOIN Persons ON Students.person_id = Persons.person_id
    JOIN Enroll ON Enroll.student_id = Students.account_number
    JOIN Section ON Section.section_id = Enroll.section_id
    JOIN Classes ON Section.class_id = Classes.class_id
    JOIN Employees AS DepartmentHead ON Section.employee_number = DepartmentHead.employee_number
    WHERE DepartmentHead.department_id = ?
      AND Section.period_id = (SELECT period_id FROM Periods WHERE active = 1 LIMIT 1)
      AND Enroll.is_canceled = 0
    GROUP BY Students.account_number, Persons.first_name, Persons.last_name;
";

// Preparar la consulta
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $departmentId);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Cerrar conexión
$stmt->close();
$db->close();

// Respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($students);
