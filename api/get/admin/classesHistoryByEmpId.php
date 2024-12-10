<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();

header('Content-Type: application/json');

// Validar el parámetro 'employee_number' en la URL
if (!isset($_GET['employee_number']) || !is_numeric($_GET['employee_number'])) {
    echo json_encode(['error' => 'El parámetro employee_number es obligatorio y debe ser numérico']);
    exit;
}

$id = intval($_GET['employee_number']);

// Verificar que el campo empleado existe en la tabla
$Carrers = $db->execute_query('SELECT S.section_id, S.hour_start, C.class_code, C.class_name  
FROM `History` E
INNER JOIN `Section` S
ON E.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE S.employee_number = ?
ORDER BY S.section_id', [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

// Devolver el resultado como JSON
echo json_encode($resultArray);
?>