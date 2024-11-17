<?php
header('Content-Type: application/json');

session_start();
$employeetid = $_SESSION['departmentid'];
$role = $_SESSION['role'];

if ($role != 'Department Head') {
    http_response_code(404);
    echo json_encode(['Message' => 'You do not have privileges to do this action']);
    return;
}

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $section_id = $data->id;  

    $result = [];

    if (isset($section_id)) {
        $deleteSectionDaysQuery = "DELETE FROM SectionDays WHERE section_id = ?";
        $stmt = $conn->prepare($deleteSectionDaysQuery);
        $stmt->bind_param("i", $section_id);  
        if ($stmt->execute()) {
            $deleteSectionQuery = "DELETE FROM Section WHERE section_id = ?";
            $stmt = $conn->prepare($deleteSectionQuery);
            $stmt->bind_param("i", $section_id); 

            if ($stmt->execute()) {
                $result[] = ["status" => 1, "message" => "Row deleted successfully"];
            } else {
                $result[] = ["message" => "Failed to delete row from Section"];
            }
        } else {
            $result[] = ["message" => "Failed to delete row from SectionDays"];
        }
        $stmt->close();
    } else {
        $result[] = ["message" => "ID not provided"];
    }
} else {
    $result[] = ["message" => "Invalid request method"];
}

echo json_encode($result);

$conn->close();


?>