<?php
header('Content-Type: application/json');

session_start();
$role = $_SESSION['role'];

if ($role != 'Department Head') {
    http_response_code(404);
    echo json_encode(['Message' => 'You do not have privileges to do this action']);
    return;
}

if (!isset($_GET['teacher_identifier'])) {
    http_response_code(404);
    echo json_encode(['Message' => 'Incorrect request, missing teacher ID']);
    return;
}

$teacher_identifier = $_GET['teacher_identifier'];

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
        WHERE Employees.employee_number = ? 
        OR Persons.person_id = ? 
        OR Employees.institute_email = ?;
';

$result = $conn->execute_query($sql, [$teacher_identifier, $teacher_identifier, $teacher_identifier]);

if ($result) {
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['Message' => 'No data found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['Message' => 'Error in the query']);
}


$conn->close();
exit;