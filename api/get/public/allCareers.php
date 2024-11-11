<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$Carrers = $db->execute_query("SELECT career_id, career_name FROM `Careers`");

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);