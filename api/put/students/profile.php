<?php
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json");

$input = file_get_contents("php://input");

$data = json_decode($input, true);

if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "Error decoding JSON data"
    ]);
    return;
} 

$account_number = $data['account_number'];
$person_id = $data['person_id'];
$email = $data['personalEmail'];
$phone = $data['phone'];
$direction = $data['direction'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "UPDATE Students SET direction = ? WHERE account_number = ?";
$conn->execute_query($sql, [$direction, $account_number]);

$sql = "UPDATE Persons SET  personal_email = ?, phone = ? WHERE person_id = ?";
$conn->execute_query($sql, [$email, $phone, $person_id]);

echo json_encode([
    "status" => "success",
    "message" => "Data updated correctly",
    "received_data" => $data
]);
?>
