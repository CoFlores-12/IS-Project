<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$result = $db->execute_query("SELECT * FROM `Exams`");

$response = [];

$Exams = [];
while ($row = $result->fetch_assoc()) {
    $Exams[] = $row;  
}
$response['Exams'] = $Exams;

$result = $db->execute_query("SELECT career_id, career_name from `Careers`");
$Careers = [];
while ($row = $result->fetch_assoc()) {
    $Careers[] = $row;  
}
$response['Careers'] = $Careers;



header('Content-Type: application/json');
echo json_encode($response);