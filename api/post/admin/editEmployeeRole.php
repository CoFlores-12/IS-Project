<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$person_id = $data['person_id'] ?? null;
$role_id = $data['role_id'] ?? null;

if (!$person_id || !$role_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$query = "UPDATE Employees SET role_id = ? WHERE person_id = ?";
$stmt = $db->prepare($query);
if ($stmt->execute([$role_id, $person_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
