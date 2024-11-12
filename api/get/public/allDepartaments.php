<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$Departments = $db->execute_query("SELECT * FROM `Departments`");

$resultArray = [];
if ($Departments) {
    while ($row = $Departments->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);