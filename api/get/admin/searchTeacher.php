<?php
header('Content-Type: application/json');

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);

if (!isset($_GET['teacher_identifier'])) {
    http_response_code(404);
    echo json_encode(['Message' => 'Bad request, missing teacher identifier']);
    return;
}

$employeeNumber = $_SESSION['user']['employeenumber'];

$teacher_identifier = $_GET['teacher_identifier'];

$response = [];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = 'SELECT Employees.employee_number,
            Employees.institute_email,
            Persons.person_id,
            Persons.first_name,
            Persons.last_name,
            Persons.phone,
            Persons.personal_email
        FROM Employees
        JOIN Persons ON Employees.person_id = Persons.person_id
        JOIN Roles ON Employees.role_id = Roles.role_id
        WHERE (Employees.employee_number = ? 
            OR Persons.person_id = ? 
            OR Employees.institute_email = ?)
        AND Employees.department_id = (
            SELECT Employees.department_id
            FROM Employees
            WHERE Employees.employee_number = ? 
            LIMIT 1
        );
        ';

$result = $conn->execute_query($sql, [$teacher_identifier, $teacher_identifier, $teacher_identifier, $employeeNumber]);

if ($result) {
    if ($row = $result->fetch_assoc()) {
        $response["row"] = $row;
        $response["status"] = 0;
    } else {
        $response["status"] = 1;
    }
} else {
    $response["status"] = 2;
}

echo json_encode($response);

$conn->close();
exit;