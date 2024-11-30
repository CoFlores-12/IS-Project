<?php
include '../../../src/modules/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sectionId = $_POST['section_id'];
    $videoUrl = $_POST['video_url'];

    if (empty($sectionId) || empty($videoUrl)) {
        echo json_encode(['error' => 'Faltan datos necesarios']);
        exit;
    }

    $conn = (new Database())->getConnection();

    $query = "INSERT INTO class_resources (section_id, video_url) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $sectionId, $videoUrl);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'save']);
    } else {
        echo json_encode(['error' => 'Error']);
    }
}
?>
