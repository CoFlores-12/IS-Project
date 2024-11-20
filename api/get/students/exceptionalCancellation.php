<?php
session_start();
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$query = "SELECT data FROM Config LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $configData = json_decode($row['data'], true);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Config not found']);
    exit;
}

$cancellationData = json_decode($configData['cancellationExceptional'], true);
$cancellationStart = new DateTime($cancellationData['startTime']);
$cancellationEnd = new DateTime($cancellationData['endTime']);

$currentDate = new DateTime();


if ($currentDate >= $cancellationStart && $currentDate <= $cancellationEnd) {
    echo json_encode(['status' => true]);
} else {
    echo json_encode(['status' => false]);
}

$conn->close();
?>
