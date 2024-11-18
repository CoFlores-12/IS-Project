<?php

include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$applicant_id = $_POST['applicant_id'];
$validate = $_POST['validate'];
$sql = "UPDATE Applicant SET validated = ? WHERE applicant_id = ?";
$stmt_insert = $conn->prepare($sql);
$stmt_insert->bind_param("ii", $validate, $applicant_id);
$stmt_insert->execute();
echo json_encode(["data"=>"action"]);