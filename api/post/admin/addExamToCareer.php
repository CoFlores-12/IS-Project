<?php

$data = json_decode(file_get_contents('php://input'), true);

$exam = $data['exam'];
$careerId = $data['career_id'];
$passingScore = $data['passing_score'];


include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

try {
        
    $sql = "INSERT INTO `Exams`(exam_code, exam_name ) VALUES (?, 'Added' );";
    $conn->execute_query($sql, [$exam]);
    
} catch (\Throwable $th) {
    
}

$sql = "INSERT INTO `ExamsXCareer`(exam_code, career_id, min_point ) VALUES (?,?,?);";
$conn->execute_query($sql, [$exam, $careerId, $passingScore]);


echo json_encode(['message' => 'Datos recibidos correctamente']);
?>
