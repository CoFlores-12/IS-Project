<?php

include '../src/modules/database.php';

$db = (new Database())->getConnection();
$response = [];


$sql = "UPDATE Config
        SET data = JSON_SET(data, '$.AdmissionsStatus', 1)
        WHERE config_id = 1;";
    $stmt = $db->prepare($sql);
    $stmt->execute();

header('Content-Type: application/json');
echo json_encode($resultArray);
?>