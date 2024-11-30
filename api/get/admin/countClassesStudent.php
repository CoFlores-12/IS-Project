<?php
header('Content-Type: application/json');


require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);


include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();


if (!isset($_GET['section_identifier'])) {
    http_response_code(404);
    echo json_encode(['Message' => 'Bad request, missing section identifier']);
    return;
}

$section_identifier = $_GET['section_identifier'];

$response = [];

$sql = '
    SELECT 
    e.student_id,
    COUNT(h.history_id) AS approved_classes,
    total_classes.total_classes - COUNT(h.history_id) AS pending_classes,
    CASE 
        WHEN total_classes.total_classes - COUNT(h.history_id) < 5 THEN TRUE
            ELSE FALSE
        END AS has_less_than_5_remaining
    FROM Enroll e
    JOIN Students st ON e.student_id = st.account_number
    LEFT JOIN History h ON e.student_id = h.student_id AND h.obs_id = 1
    JOIN (
        SELECT 
            cxc.career_id,
            COUNT(cxc.class_id) AS total_classes
        FROM ClassesXCareer cxc
        GROUP BY cxc.career_id
    ) AS total_classes ON st.career_id = total_classes.career_id
    WHERE e.section_id = ?
    GROUP BY e.student_id, total_classes.total_classes;
';

$result = $conn->execute_query($sql, [$section_identifier]);

if ($result) {
    if ($result->num_rows > 0) {
        $response["rows"] = [];
        while ($row = $result->fetch_assoc()) {
            $response["rows"][] = $row; 
        }
        $response["status"] = 0;  
    } else {
        $response["status"] = 1; 
    }
} else {
    $response["status"] = 2; 
}

echo json_encode($response);

$conn->close();
exit;