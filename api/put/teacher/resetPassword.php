<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];  
$new_password = $data['new_password']; 

include '../../../src/modules/database.php'; 
$conn = (new Database())->getConnection();

$query = "
    SELECT identifier, expires_at 
    FROM PasswordResetTokens
    WHERE token = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);  

$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt')) AS phraseEncrypt FROM Config WHERE config_id = 1";
$result1 = $conn->query($sql);

if ($result1 && $row = $result1->fetch_assoc()) {
    $passphrase = $row['phraseEncrypt'];  
} 

$response = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $expiration_time = $row['expires_at'];

    $current_time = new DateTime();
    $expiration_time = new DateTime($expiration_time);

    if ($current_time < $expiration_time) {

        $update_query = "UPDATE Employees SET password =  AES_ENCRYPT(?, ?) WHERE employee_number = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param('ssi', $new_password, $passphrase , $row['identifier']);
        
        if ($stmt_update->execute()) {
            $response["status"] = 0;
            $invalidate_query = "UPDATE PasswordResetTokens SET is_used = 1 WHERE token = ?";
            $stmt_invalidate = $conn->prepare($invalidate_query);
            $stmt_invalidate->bind_param('s', $token);
            $stmt_invalidate->execute();

        } else {
            $response["status"] = 1;
        }
    } else {
        $response["status"] = 3;
    }
} else {
    $response["status"] = 4;
}

echo json_encode($response);

$conn->close();
?>
