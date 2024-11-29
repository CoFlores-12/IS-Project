<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();
session_start();
$id = $_SESSION['user']['student_id'];
$enrollPeriod = true;
$cancelPeriod = true;

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

$result = $db->execute_query("SELECT 
    JSON_UNQUOTE(JSON_EXTRACT(data, '$.EnrollPeriod')) as EnrollPeriod,
    JSON_UNQUOTE(JSON_EXTRACT(data, '$.cancellationExceptional')) as cancellationExceptional
        FROM Config
        WHERE config_id = 1;");
$dataResult = $result->fetch_assoc();
$response['EnrollPeriod'] = json_decode($dataResult['EnrollPeriod'], true);
$response['cancellationExceptional'] = json_decode($dataResult['cancellationExceptional'], true);

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
    $enrollPeriod = false;
} 

$start = $response['cancellationExceptional']['startTime'];
$end = $response['cancellationExceptional']['endTime'];

if (!isDateInRange($start, $end)) {
    $cancelPeriod = false;
}

$result = $db->execute_query('SELECT status FROM  `Requests` r
INNER JOIN `Periods` p on r.period_id = p.period_id
WHERE p.active = 1 AND request_type_id = 2 AND r.student_id = ?', [$id]);
$row = $result->fetch_assoc();
if ($row['status'] != 1) {
    $cancelPeriod = false;
}

if (!$enrollPeriod && !$cancelPeriod) {
    header('Content-Type: application/json');
    echo json_encode([
        "status"=>false,
        "message"=>"Periodo de cancelación no activo."
    ]);
    exit;
    
}

$Carrers = $db->execute_query('SELECT S.section_id, E.enroll_id, S.hour_start, C.class_code, C.class_name class_name, is_waitlist  FROM `Enroll` E
INNER JOIN `Section` S
ON E.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE E.student_id = ?', [$id]);

$resultArray = [];
if ($Carrers) {
    while ($row = $Carrers->fetch_assoc()) {
        $resultArray[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'status'=> true,
    'data'=>$resultArray
]);