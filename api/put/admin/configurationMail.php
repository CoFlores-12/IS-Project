<?php
include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nextDay = date('Y-m-d', strtotime('+1 day'));

        $data = [
            'enabled' => true,
            'sendTime' => '00:00',
            'activationDate' => $nextDay
        ];

        $sql = "
            UPDATE Config
            SET data = JSON_SET(data, '$.emailSendActivation', ?)
            WHERE config_id = 1;
        ";

        $result = $conn->execute_query($sql, [json_encode($data)]);

        if ($result) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error occurred: " . $e->getMessage()]);
    }

    $sql = "UPDATE Config
        SET data = JSON_SET(data, '$.AdmissionsStatus', ?)
        WHERE config_id = 1;";
    $conn->execute_query($sql, [0]);

    echo json_encode($result);
} else {
    echo json_encode($response);
}
