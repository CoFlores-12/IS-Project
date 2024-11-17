<?php
header('Content-Type: application/json');

require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);

$employeetid = $_SESSION['user']['employeenumber'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

try {
    $input = json_decode(file_get_contents("php://input"), true);

    $section_id = $input['section_id'];
    $reason = $input['reason'];

    if (!$section_id) {
        http_response_code(400);
        echo json_encode(["message" => "section_id y cancelled_by son obligatorios."]);
        exit();
    }

    $currentDate = date("Y-m-d");

    $sql = "INSERT INTO CancelledSections (section_id, cancel_date, reason, cancelled_by) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $section_id, $currentDate, $reason, $employeetid);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Sección cancelada con éxito."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error al cancelar la sección.", "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error del servidor.", "error" => $e->getMessage()]);
}
?>
