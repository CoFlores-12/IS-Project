<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['student_id'];

$index = $db->execute_query("SELECT 
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND p1.active = 0 
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
    FROM 
        History h
    JOIN 
        `Section` s ON h.section_id = s.section_id
    JOIN 
        `Classes` c ON s.class_id = c.class_id
    WHERE 
        h.student_id = ?
    GROUP BY 
        h.student_id;
    ", [$id]);
$indexRow = $index->fetch_assoc();

$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.EnrollPeriod')) as EnrollPeriod
        FROM Config
        WHERE config_id = 1;");
$response['EnrollPeriod'] = json_decode($result->fetch_assoc()['EnrollPeriod'], true);

$indiceGlobal = $indexRow['indice_global'];
$indiceUltimoPeriodo = $indexRow['indice_ultimo_periodo']; 

function isDateInRange($start, $end) {
    $currentDate = new DateTime(); 
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    
    return $currentDate >= $startDate && $currentDate <= $endDate;
}

if ($indiceGlobal >= 84) {
    $periodo = 1;
} elseif ($indiceUltimoPeriodo >= 71) {
    $periodo = 2;
} else {
    $periodo = 3;
}


$start = $response['EnrollPeriod'][$periodo]['start'];
$end = $response['EnrollPeriod'][$periodo]['end'];

if (!isDateInRange($start, $end)) {
    header('Content-Type: application/json');
    echo json_encode([
        "status"=>false,
        "message"=>"Su periodo de matrícula es del " . $start . " al " . $end . "."
    ]);
    exit;
    
} 


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
                   WHERE H.student_id = ? AND H.obs_id = 1
               )
           ) THEN 1
           ELSE 0
       END AS estado
FROM `ClassesXCareer` A
INNER JOIN `Classes` B ON A.class_id = B.class_id COLLATE utf8mb4_unicode_ci
WHERE A.career_id = (
    SELECT career_id 
    FROM `Students` 
    WHERE account_number = ? COLLATE utf8mb4_unicode_ci
)
AND B.class_id NOT IN (
    SELECT S.class_id COLLATE utf8mb4_unicode_ci
    FROM `History` H
    INNER JOIN `Section` S ON H.section_id = S.section_id 
    WHERE H.student_id = ? AND H.obs_id = 1
)
HAVING estado = 1;
", [$id,$id,$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    "status"=>true,
    "data"=>$resultArray
]);