<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

session_start();
$id = $_SESSION['studentID'];
$request_id = $_POST['request_type_id'];
$comments = $_POST['comments'];

if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === UPLOAD_ERR_OK) {
    $fileSize = $_FILES['evidence']['size'];

    if ($fileSize > 16777215) { 
        echo json_encode(['message' => 'File size is too large.']);
        exit;
    }
    $evidenceTmpPath = $_FILES['evidence']['tmp_name'];
    $evidence = file_get_contents($evidenceTmpPath);
} else {
    $evidence = null; 
}
if (isset($_POST['career_change_id'])) {
    $career_change_id = $_POST['career_change_id'];
}else{
    $career_change_id = null;
}
if (isset($_POST['campus_change_id'])) {
    $campus_change_id = $_POST['campus_change_id'];
}else{
    $campus_change_id = null;
}

$sql = 'insert into `Requests` (student_id, request_type_id, comments, evidence, career_change_id, campus_change_id)
values(?, ?, ?, ?, ?, ?);';
$result = $conn->execute_query($sql,[$id, $request_id, $comments, $evidence, $career_change_id, $campus_change_id]);
echo json_encode(["message"=>"Created"]); 