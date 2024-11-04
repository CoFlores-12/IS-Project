<?php

//get data from form
parse_str(file_get_contents("php://input"), $data);

$account_number = isset($data['account_number']) ? $data['account_number'] : null;
$filename = isset($data['filename']) ? $data['filename'] : null;

//validate data
if (!$account_number || !$filename) {
    http_response_code(400);
    echo json_encode(["message" => "Missing parameters: account_number and filename are required."]);
    return;
}

//conneted to DB
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "SELECT photos FROM Students WHERE account_number = ?";
$result = $conn->execute_query($sql, [$account_number]);

//validate Student exist
if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["message" => "Student not fount."]);
    return;
}

$row = $result->fetch_assoc();
$photos = json_decode($row['photos'], true);

//get file to delete
$uploadDir = '../../../uploads/';
$filePath = $uploadDir . $filename;

//delete file from file system
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

//delete file from student array
unset($photos[$indexToRemove]);

$updated_photos = json_encode($photos);

//save array without photo deleted
$update_sql = "UPDATE Students SET photos = ? WHERE account_number = ?";
$conn->execute_query($update_sql, [$updated_photos, $account_number]);

echo json_encode(["message" => "Photo deleted!"]);

$conn->close();
?>
