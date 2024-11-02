<?php


parse_str(file_get_contents("php://input"), $data);

$account_number = isset($data['account_number']) ? $data['account_number'] : null;
$filename = isset($data['filename']) ? $data['filename'] : null;

if (!$account_number || !$filename) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan parámetros: account_number y filename son requeridos."]);
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "SELECT photos FROM Students WHERE account_number = ?";
$result = $conn->execute_query($sql, [$account_number]);

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["message" => "Estudiante no encontrado."]);
    return;
}

$row = $result->fetch_assoc();
$photos = json_decode($row['photos'], true);

$uploadDir = '../../../uploads/';
$filePath = $uploadDir . $filename;

if (file_exists($filePath)) {
    unlink($filePath);
}

$indexToRemove = null;

foreach ($photos as $index => $url) {
    if (basename($url) === $filename) { 
        $indexToRemove = $index;
        break;
    }
}

unset($photos[$indexToRemove]);

$updated_photos = json_encode($photos);

$update_sql = "UPDATE Students SET photos = ? WHERE account_number = ?";
$conn->execute_query($update_sql, [$updated_photos, $account_number]);

echo json_encode(["message" => "Photo deleted!"]);

$conn->close();
?>
