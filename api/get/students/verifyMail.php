<?php
header('Content-Type: application/json');
include '../../../src/modules/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$institute_email = isset($_GET['institute_email']) ? $_GET['institute_email'] : '';

$response = [];

if (!empty($institute_email)) {
    $conn = (new Database())->getConnection();

    $query = "SELECT account_number, institute_email  FROM Students WHERE institute_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $institute_email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['student_id'] = $row['account_number'];
        $response['status'] = 0;
    } else {
        $response['status'] = 1;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['status'] = 2;
}

echo json_encode($response);
?>
