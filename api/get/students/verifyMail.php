<?php
header('Content-Type: application/json');
include '../../../src/modules/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$personal_email = isset($_GET['personal_email']) ? $_GET['personal_email'] : '';

$response = [];

if (!empty($personal_email)) {
    $conn = (new Database())->getConnection();

    $query = "SELECT account_number, personal_email  FROM `Persons` p JOIN `Students` s ON s.person_id = p.person_id WHERE personal_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $personal_email);

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
