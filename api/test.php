<?php

include '../src/modules/database.php';

$db = (new Database())->getConnection();
$response = [
    "1"=> [
        "start"=>"2024-12-03T10:59",
        "end"=>"2024-12-03T10:59"
    ],
    "2"=> [
        "start"=>"2024-12-03T10:59",
        "end"=>"2024-12-03T10:59"
    ],
    "3"=> [
        "start"=>"2024-12-03T10:59",
        "end"=>"2024-12-03T10:59"
    ]
];


$sql = "UPDATE Config
        SET data = JSON_SET(data, '$.AdmissionsStatus', 0)
        WHERE config_id = 1;";
    $stmt = $db->prepare($sql);
    $jsonResponse = json_encode($response);
    $stmt->execute();

header('Content-Type: application/json');
echo json_encode($response);
?>