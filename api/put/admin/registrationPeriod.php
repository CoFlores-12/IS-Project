<?php

include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];

$data = ['startTime'=>$startTime, 'endTime'=>$endTime];

$sql = "UPDATE Config
SET data = JSON_SET(data, '$.registrationPeriod', ?)
WHERE config_id = 1;";

$result = $conn->execute_query($sql, [json_encode($data)]);

echo json_encode(["message"=>"updated"]);