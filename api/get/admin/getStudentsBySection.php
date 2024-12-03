<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

header('Content-Type: application/json');

// Verificar si se envió el parámetro `section_id`
if (!isset($_GET['section_id'])) {
    echo json_encode(['error' => 'El parámetro section_id es requerido']);
    exit();
}

$section_id = $_GET['section_id'];

$query = "
    SELECT 
        CONCAT(p.first_name, ' ', p.last_name) AS full_name,
        s.account_number,
        s.institute_email
    FROM Enroll e
    JOIN Students s ON e.student_id = s.account_number
    JOIN Persons p ON s.person_id = p.person_id
    WHERE e.section_id = ?
";

$stmt = $db->prepare($query);
$stmt->bind_param('i', $section_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);
?>
