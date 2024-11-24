<?php
include './../../../src/modules/database.php';

// Inicializa la respuesta para asegurarse de que es JSON
$response = ['success' => false, 'message' => ''];

try {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("CALL validate_obs_paa()");
    $stmt->execute();
    $response['success'] = true;
    $response['message'] = 'Procedure Successfully Executed.';

    $sql = "UPDATE Config
        SET data = JSON_SET(data, '$.AdmissionsStatus', 2)
        WHERE config_id = 1;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
} catch (PDOException $e) {
    $response['message'] = 'Data Base Error: ' . $e->getMessage();
}

// Configura la cabecera para JSON y envía la respuesta
header('Content-Type: application/json');
echo json_encode($response);
?>
