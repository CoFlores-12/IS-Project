<?php

header('Content-Type: application/json');

$token = $_GET['token']; 

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$query = "
    SELECT employee_id, expires_at, is_used
    FROM PasswordResetTokens
    WHERE token = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token); 

$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $expiration_time = $row['expires_at'];
    $used = $row['is_used'];

    $current_time = new DateTime();
    $expiration_time = new DateTime($expiration_time);

    $response["now"] = $current_time;
    $response["end"] = $expiration_time;

    if ($current_time < $expiration_time && $used == false) {
        $response["status"] = 0;
    } else {
        $response = ["status" => 1];
    }
} else {
    $response = ["status" => 2];
}

echo json_encode($response);

$conn->close();
?>
