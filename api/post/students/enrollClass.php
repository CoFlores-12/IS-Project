<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

if (!isset($_POST['section_id']) || !is_numeric($_POST['section_id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid section_id'.$_POST['section_id']
    ]);
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

session_start();
$id = $_SESSION['studentID'];
$section_id = $_POST['section_id'];
$status = false;
$message = 'Error in server';

try {
    $is_waitlist = 0;
    $result = $conn->execute_query("SELECT 
                S.quotas - (
                    SELECT COUNT(*) FROM `Enroll` E
                    WHERE E.section_id = S.section_id AND is_waitlist = 0
                ) as quotas
            FROM `Section` S
            WHERE section_id = ?", [$section_id]);
    $row = $result->fetch_assoc();
    $quotas = $row['quotas'];
    if ($quotas == 0) {
        $is_waitlist = 1;
    }
    $query_insert = "INSERT INTO Enroll (student_id, section_id, is_waitlist) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iii", $id, $section_id, $is_waitlist);
    $stmt_insert->execute();
    if (!$result) {
        new Error("error when registering the class");
    }
    $status = true;
    $message = 'successfully enrolled class';
} catch (\Throwable $th) {
    $message = "error when registering the class".$th;
}

echo json_encode([
    'status'=>$status,
    'message'=> $message
]);