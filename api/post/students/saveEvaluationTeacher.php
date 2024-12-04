<?php

session_start();
$idStudent = $_SESSION['user']['student_id'] ?? null;

header("Content-Type: application/json");
include '../../../src/modules/database.php';

$conn = (new Database())->getConnection();

// Recupera los valores del cuerpo de la solicitud
$section_id = $_POST['section_id'] ?? null;
$responses = $_POST['responses'] ?? null;

if (empty($section_id) || empty($responses) || empty($idStudent)) {
    echo json_encode(["status" => false, "message" => "All fields are required."]);
    exit;
}

try {
    // Decodificamos las respuestas (si son JSON)
    $responses = json_decode($responses, true);
    if (!$responses) {
        echo json_encode(["status" => false, "message" => "Invalid responses format."]);
        exit;
    }

    // Obtenemos el ID del docente asociado a la sección
    $sql = "SELECT employee_number FROM Section WHERE section_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $section_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();
    $teacherId = $section['employee_number'];

    // Si no se encuentra el docente, lanzamos un error
    if (empty($teacherId)) {
        echo json_encode(["status" => false, "message" => "Teacher not found for this section."]);
        exit;
    }

    // Guardamos la evaluación
    $responsesJson = json_encode($responses, JSON_UNESCAPED_UNICODE);

    // Inserción en la base de datos
    $stmt = $conn->prepare("INSERT INTO student_teacher_evaluation (student_account_number, teacher_id, responses, section_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $idStudent, $teacherId, $responsesJson, $section_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => true, "message" => "Evaluation saved successfully."]);
    } else {
        echo json_encode(["status" => false, "message" => "Error saving evaluation."]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>
