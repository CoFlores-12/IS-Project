<?php

include '../src/modules/database.php';

$db = (new Database())->getConnection();
$response = [];

$result = $db->execute_query("SELECT * FROM LogAuth L left join Roles R on L.role_id = R.role_id");

$resultArray = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);
?>