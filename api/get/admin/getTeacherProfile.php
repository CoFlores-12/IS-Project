<?php
header('Content-Type: application/json');

include './../../../src/modules/database.php'; // Ruta del archivo de conexión a la base de datos
$db = (new Database())->getConnection();

if ($db->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $db->connect_error]);
    exit;
}

// Verificar si se proporcionó el número de empleado en la URL
if (!isset($_GET['employee_number'])) {
    echo json_encode(["error" => "employee_number is required"]);
    exit;
}

$employee_number = $db->real_escape_string($_GET['employee_number']);

// Consultar datos de Employees
$sql = "SELECT person_id, role_id, institute_email, department_id FROM Employees WHERE employee_number = '$employee_number'";
$result = $db->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Employee not found"]);
    exit;
}

$employee = $result->fetch_assoc();

// Consultar datos de Persons
$sql = "SELECT first_name, last_name, personal_email, phone FROM Persons WHERE person_id = '{$employee['person_id']}'";
$result = $db->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Person not found"]);
    exit;
}

$person = $result->fetch_assoc();

// Consultar tipo de rol
$sql = "SELECT type FROM Roles WHERE role_id = '{$employee['role_id']}'";
$result = $db->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Role not found"]);
    exit;
}

$role = $result->fetch_assoc();

// Consultar nombre del departamento
$sql = "SELECT department_name FROM Departments WHERE department_id = '{$employee['department_id']}'";
$result = $db->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Department not found"]);
    exit;
}

$department = $result->fetch_assoc();

// Preparar respuesta
$response = [
    "fullName" => $person['first_name'] . " " . $person['last_name'],
    "personalEmail" => $person['personal_email'],
    "instituteEmail" => $employee['institute_email'],
    "phone" => $person['phone'],
    "role" => $role['type'],
    "department" => $department['department_name']
];

echo json_encode($response);

$db->close();
?>
