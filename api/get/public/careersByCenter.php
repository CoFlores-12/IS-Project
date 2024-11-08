<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$center_id = $_GET['center_id'];
$Carrers = $db->execute_query("SELECT * FROM `Careers` A INNER JOIN `CareersXRegionalCenter` B ON A.career_id = B.career_id WHERE B.center_id = ?", [$center_id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);