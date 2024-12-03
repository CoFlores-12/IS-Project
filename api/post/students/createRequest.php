<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

session_start();
$id = $_SESSION['user']['student_id'];
$request_id = $_POST['request_type_id'];
$comments = $_POST['comments'];
$evidence = null;
$evidence_ext = null;
$sections = null;

if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === UPLOAD_ERR_OK) {
    $fileSize = $_FILES['evidence']['size'];

    if ($fileSize > 4*1024*1024) { 
        echo json_encode([
            'status'=>false,
            'message' => 'Archivo mayor a 4MB']
        );
        exit;
    }
    $originalFileName = $_FILES['evidence']['name'];
    $evidenceTmpPath = $_FILES['evidence']['tmp_name'];
    $evidence = file_get_contents($evidenceTmpPath);
    $evidence_ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $sections = $_POST['sections'];

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

$result = $conn->execute_query('SELECT period_id FROM Periods where active = true');
$row = $result->fetch_assoc();

$sql = 'insert into `Requests` (student_id, request_type_id, comments, evidence, evidence_ext, career_change_id, campus_change_id, period_id, classes_cancel)
values(?, ?, ?, ?, ?, ?, ?, ?, ?);';
$result = $conn->execute_query($sql,[$id, $request_id, $comments, $evidence, $evidence_ext, $career_change_id, $campus_change_id, $row['period_id'], $sections]);
echo json_encode([
    "status"=>true,    
    "message"=>"Created"]); 