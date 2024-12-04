<?php

include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$startTime = $_POST['start_time'];
$endTime = $_POST['end_time'];

$data = ['startTime'=>$startTime, 'endTime'=>$endTime];

$sql = "UPDATE Config
SET data = JSON_SET(data, '$.uploadNotes', ?)
WHERE config_id = 1;";

$result = $conn->execute_query($sql, [json_encode($data)]);

echo json_encode(["message"=>"updated"]);