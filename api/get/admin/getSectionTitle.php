<?php
include './../../../src/modules/database.php'; // Ruta del archivo de conexión a la base de datos
$db = (new Database())->getConnection();

header('Content-Type: application/json');

// Validar el parámetro 'section_id' en la URL
if (!isset($_GET['section_id']) || !is_numeric($_GET['section_id'])) {
    echo json_encode(['error' => 'El parámetro section_id es obligatorio y debe ser numérico']);
    exit;
}

$section_id = intval($_GET['section_id']);

try {
    // Consulta para obtener el class_id desde la tabla Section
    $query = "SELECT class_id FROM Section WHERE section_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $section_id);
    $stmt->execute();
    $section_result = $stmt->get_result();

    if ($section_result->num_rows === 0) {
        echo json_encode(['error' => 'Sección no encontrada']);
        exit;
    }

    $section = $section_result->fetch_assoc();
    $class_id = $section['class_id'];

    // Consulta para obtener class_name y class_code desde la tabla Classes
    $query = "SELECT class_name, class_code FROM Classes WHERE class_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    $class_result = $stmt->get_result();

    if ($class_result->num_rows === 0) {
        echo json_encode(['error' => 'Clase no encontrada']);
        exit;
    }

    $class = $class_result->fetch_assoc();
    $response = [
        'class_name' => $class['class_name'],
        'class_code' => $class['class_code'],
        'title' => "{$class['class_name']} - {$class['class_code']}"
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
