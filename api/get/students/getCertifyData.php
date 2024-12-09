<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();

// Obtener el ID del estudiante desde la sesión
$id = $_SESSION['user']['student_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

// Validar que el ID esté presente
if (!$id) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['error' => 'El ID del estudiante no está en la sesión.']);
    exit;
}

// Consultar encabezado
$headerQuery = $db->prepare("
    SELECT `Persons`.first_name, `Persons`.last_name, `Students`.account_number, `Careers`.career_name
    FROM `Students`
    INNER JOIN `Persons` ON `Persons`.person_id = `Students`.person_id
    INNER JOIN `Careers` ON `Careers`.career_id = `Students`.career_id
    WHERE `Students`.account_number = ?
");
$headerQuery->bind_param("i", $id);
$headerQuery->execute();
$headerResult = $headerQuery->get_result();
$header = $headerResult->fetch_assoc();

if (!$header) {
    http_response_code(404); // No encontrado
    echo json_encode(['error' => 'Estudiante no encontrado.']);
    exit;
}

// Consultar clases
$classesQuery = $db->prepare("
    SELECT `Classes`.class_code, `Classes`.class_name, `Enroll`.score, `Classes`.uv
    FROM `Classes`
    INNER JOIN `Section` ON `Classes`.class_id = `Section`.class_id
    INNER JOIN `Enroll` ON `Section`.section_id = `Enroll`.section_id
    WHERE `Enroll`.student_id = ?
");
$classesQuery->bind_param("i", $id);
$classesQuery->execute();
$classesResult = $classesQuery->get_result();
$classes = [];

while ($row = $classesResult->fetch_assoc()) {
    $classes[] = $row;
}

// Respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode([
    'header' => $header,
    'classes' => $classes
]);
?>
