<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();

// Obtener el número de empleado desde la sesión
$employeeNumber = $_SESSION['user']['employeenumber'];

// Consultar el department_id del empleado actual
$departmentQuery = $db->prepare("SELECT department_id FROM `Employees` WHERE employee_number = ?");
$departmentQuery->bind_param("i", $employeeNumber);
$departmentQuery->execute();
$departmentResult = $departmentQuery->get_result();
$department = $departmentResult->fetch_assoc();

if (!$department) {
    // Si no se encuentra el departamento, devolvemos un error
    http_response_code(404);
    echo json_encode(["error" => "Empleado no encontrado o sin departamento asignado"]);
    exit;
}

$departmentId = $department['department_id'];

// Configurar paginación
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? intval($_GET['itemsPerPage']) : 10;
$offset = ($page - 1) * $itemsPerPage;

// Consultar el total de registros filtrados por departamento
$totalQuery = $db->prepare("SELECT COUNT(*) as total FROM `Enroll`
    INNER JOIN `Section` ON `Enroll`.section_id = `Section`.section_id
    INNER JOIN `Classes` ON `Section`.class_id = `Classes`.class_id
    INNER JOIN `Employees` ON `Section`.employee_number = `Employees`.employee_number
    INNER JOIN `Persons` ON `Employees`.person_id = `Persons`.person_id
    INNER JOIN `Periods` ON `Section`.period_id = `Periods`.period_id
    WHERE `Periods`.active = true AND `Employees`.department_id = ?");
$totalQuery->bind_param("i", $departmentId);
$totalQuery->execute();
$totalResult = $totalQuery->get_result();
$total = $totalResult->fetch_assoc()['total'];

// Consultar los datos paginados filtrados por departamento
$dataQuery = $db->prepare("SELECT 
        `Classes`.class_code, 
        `Persons`.first_name, 
        `Persons`.last_name, 
        `Enroll`.student_id, 
        `Periods`.indicator, 
        `Periods`.year, 
        `Enroll`.score
    FROM 
        `Enroll`
    INNER JOIN `Section` ON `Enroll`.section_id = `Section`.section_id
    INNER JOIN `Classes` ON `Section`.class_id = `Classes`.class_id
    INNER JOIN `Employees` ON `Section`.employee_number = `Employees`.employee_number
    INNER JOIN `Persons` ON `Employees`.person_id = `Persons`.person_id
    INNER JOIN `Periods` ON `Section`.period_id = `Periods`.period_id
    WHERE `Periods`.active = true AND `Employees`.department_id = ?
    LIMIT ?, ?");
$dataQuery->bind_param("iii", $departmentId, $offset, $itemsPerPage);
$dataQuery->execute();
$dataResult = $dataQuery->get_result();

// Formatear los resultados
$data = [];
while ($row = $dataResult->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'data' => $data,
    'total' => $total,
    'page' => $page,
    'itemsPerPage' => $itemsPerPage
]);
?>
