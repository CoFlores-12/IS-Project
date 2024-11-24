<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$section_id = $data['section_id']; 
$new_quotas = $data['new_quotas']; 

$response = [];

if (!isset($section_id) || !isset($new_quotas) || !is_numeric($new_quotas)) {
    $response['status'] = 400; 
    $response['message'] = 'Invalid parameters.';
    echo json_encode($response);
    exit;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$query = "
    SELECT capacity
    FROM Section S
    INNER JOIN Classroom C ON S.classroom_id = C.classroom_id
    WHERE section_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $section_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$max_capacity = $row['capacity'];

if ($new_quotas < 10) {
    $response['status'] = 400; 
    echo json_encode($response);
    exit;
}

if ($new_quotas > $max_capacity) {
    $response['status'] = 400; 
    echo json_encode($response);
    exit;
}

$update_query = "UPDATE Section SET quotas = ? WHERE section_id = ?";
$stmt_update = $conn->prepare($update_query);
$stmt_update->bind_param('ii', $new_quotas, $section_id);

if ($stmt_update->execute()) {
    $response['status'] = 200; 
} 

echo json_encode($response);

$conn->close();
?>
