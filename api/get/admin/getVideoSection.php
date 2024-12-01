<?php
include '../../../src/modules/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sectionId = isset($_GET['section_id']) ? $_GET['section_id'] : null;

    $conn = (new Database())->getConnection();

    if ($sectionId) {
        $query = "SELECT video_url FROM class_resources WHERE section_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $sectionId); 

        $stmt->execute();
        $result = $stmt->get_result();
        
        $resource = $result->fetch_assoc();

        if ($resource) {
            echo json_encode($resource);  
        } else {
            echo json_encode(['error' => 'No se encontraron recursos para esta sección.']);
        }
    }else{
        echo json_encode(['error' => 'ID de sección no proporcionado']);
    }
}
?>
