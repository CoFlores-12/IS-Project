<?php
include_once '../../../src/modules/database.php';

$conn = (new Database())->getConnection();

$response = [];

$sql = "SELECT data FROM Config WHERE config_id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$configData = json_decode($row['data'], true);

$currentDate = new DateTime();
$scheduledDate = new DateTime($configData['emailSendTime']['day'] . ' ' . $configData['emailSendTime']['hour']);

if ($currentDate >= $scheduledDate && $configData['emailSendTime']['active'] === true) {
    $apiUrl = "http://localhost/api/post/admissions/sendMail.php"; 

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $response['status'] = 0;
    } else {
        $response['status'] = 1;
    }

    $sqlUpdate = "UPDATE Config SET data = JSON_SET(data, '$.emailSendTime.active', false) WHERE config_id = 1";
    $conn->query($sqlUpdate);
} else {
    $response['status'] = 2;
}

echo json_encode($response);

?>
