<?php 

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo "No se pudo leer el cuerpo de la solicitud JSON.";
    return;
}

$photoURL = $data['photo_url'] ?? '';
$account_number = $data['account_number'] ?? '';

if (!$account_number) {
    echo "El número de cuenta es obligatorio.";
    return;
}

$URLstored = $photoURL; 

include '../../../src/modules/database.php';

$conn = (new Database())->getConnection();

$sql = "SELECT photos FROM Students WHERE account_number = ?";
$result = $conn->execute_query($sql, [$account_number]);
$row = $result->fetch_assoc();
$photos = json_decode($row['photos'], true);

if (!is_array($photos)) {
    $photos = [$URLstored];
}else {
    $photos[] = $URLstored;
}

$updated_photos = json_encode($photos);

$update_sql = "UPDATE Students SET photos = ? WHERE account_number = ?";
$conn->execute_query($update_sql, [$updated_photos, $account_number]);

echo json_encode(['success' => true, 'message' => $data]);
$conn->close();
