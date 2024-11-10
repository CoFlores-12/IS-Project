<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$Carrers = $db->execute_query("SELECT center_id, center_name FROM `Regional_center`");

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);