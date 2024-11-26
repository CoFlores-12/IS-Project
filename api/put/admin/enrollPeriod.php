<?php

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Administrator';

AuthMiddleware::checkAccess($requiredRole);

include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();


$data = [
    "1"=> [
        "start"=>$_POST['EnrollIn1S'],
        "end"=>$_POST['EnrollIn1F']
    ],
    "2"=> [
        "start"=>$_POST['EnrollIn2S'],
        "end"=>$_POST['EnrollIn2F']
    ],
    "3"=> [
        "start"=>$_POST['EnrollIn3S'],
        "end"=>$_POST['EnrollIn3F']
        ]
    ];
    

$sql = "UPDATE Config
SET data = JSON_SET(data, '$.EnrollPeriod', ?)
WHERE config_id = 1;";

$result = $conn->execute_query($sql, [json_encode($data)]);

echo json_encode(["message"=>"updated"]);