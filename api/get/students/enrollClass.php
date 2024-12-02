<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['student_id'];
$Carrers = $db->execute_query("SELECT 
        c.class_name AS nombre_clase,
        s.hour_start AS hora_inicio,
        s.hour_end AS hora_fin,
        rc.center_name AS centro_estudio
    FROM 
        Enroll e
    JOIN 
        Section s ON e.section_id = s.section_id
    JOIN 
        Classes c ON s.class_id = c.class_id
    JOIN 
        Classroom cl ON s.classroom_id = cl.classroom_id
    JOIN 
        Building b ON cl.building_id = b.building_id
    JOIN 
        Regional_center rc ON b.center_id = rc.center_id
    WHERE 
        e.student_id = ?;", [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($resultArray);
