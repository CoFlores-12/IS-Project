<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
$Carrers = $db->execute_query("SELECT B.*, 
       CASE 
           WHEN A.req IS NULL THEN 1
           WHEN NOT EXISTS (
               SELECT 1
               FROM JSON_TABLE(
                   A.req COLLATE utf8mb4_unicode_ci, '$[*]' COLUMNS (class_code VARCHAR(10) PATH '$')
               ) AS ReqList
               WHERE ReqList.class_code COLLATE utf8mb4_unicode_ci NOT IN (
                   SELECT C.class_code COLLATE utf8mb4_unicode_ci
                   FROM `History` H
                   INNER JOIN `Section` S ON H.section_id = S.section_id
                   INNER JOIN `Classes` C ON S.class_id = C.class_id
                   WHERE H.student_id = 20201000005 AND H.obs_id = 1
               )
           ) THEN 1
           ELSE 0
       END AS estado
FROM `ClassesXCareer` A
INNER JOIN `Classes` B ON A.class_id = B.class_id COLLATE utf8mb4_unicode_ci
WHERE A.career_id = (
    SELECT career_id 
    FROM `Students` 
    WHERE account_number = 20201000005 COLLATE utf8mb4_unicode_ci
)
AND B.class_id NOT IN (
    SELECT S.class_id COLLATE utf8mb4_unicode_ci
    FROM `History` H
    INNER JOIN `Section` S ON H.section_id = S.section_id 
    WHERE H.student_id = 20201000005 AND H.obs_id = 1
)
HAVING estado = 1;
");

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);