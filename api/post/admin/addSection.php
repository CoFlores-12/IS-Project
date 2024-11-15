<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
    exit();
} 

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$response = [];

$data = json_decode(file_get_contents("php://input"), true);

$classId = $data['classId'] ?? null;
$starttime = $data['starttime'] ?? null;
$endtime = $data['endtime'] ?? null;
$classroomId = $data['classroomId'] ?? null;
$teacherId = $data['teacherId'] ?? null;
$quotas = $data['quotas'] ?? null;
$days = $data['days'] ?? null;


if (!empty($classId) && !empty($starttime) && !empty($endtime) && !empty($classroomId) && !empty($teacherId) && !empty($quotas)) {
    
    $sql = "SELECT period_id FROM Periods WHERE active = 1";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $periodId = $row['period_id'];

    $sql = "CALL CheckClassroomAvailability(?, ?, ?, ?, @is_available)";
    $result = $conn->execute_query($sql, [$classroomId, $starttime, $endtime, $days]);
    $result = $conn->query("SELECT @is_available AS is_available");
    
    $row = $result->fetch_assoc();
    
    $is_available1 = $row['is_available'];
 
    $sql = "CALL CheckInstructorAvailability(?, ?, ?, ?, @is_available)";
    $result = $conn->execute_query($sql, [$teacherId, $starttime, $endtime, $days]);
    $result = $conn->query("SELECT @is_available AS is_available");
    
    $row = $result->fetch_assoc();
    
    $is_available2 = $row['is_available'];
    if(!$is_available1 and !$is_available2 ){
        $response['status'] = 0;
        $response["message"] = ["Classroom busy, choose another time and Busy teacher, choose another time"];
    }elseif(!$is_available1){
        $response['status'] = 1;
        $response["message"] = ["Classroom busy, choose another time"];
    }elseif(!$is_available2){
        $response['status'] = 2;
        $response["message"] = ["Busy teacher, choose another time"];
    }else{

        $sql = "INSERT INTO Section (class_id, hour_start, hour_end, classroom_id, quotas, employee_number, period_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $conn->execute_query($sql, [$classId, $starttime, $endtime, $classroomId,$quotas,$teacherId, $periodId]);
        
        $section_id = $conn->insert_id;

        $days_values = [
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0
        ];

        $selected_days = explode(',', $days);
        foreach ($selected_days as $day) {
            $day = trim($day);
            if (array_key_exists($day, $days_values)) {
                $days_values[$day] = 1;
            }
        }

        $SQL = "INSERT INTO SectionDays (section_id, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $conn->execute_query($SQL, array_merge([$section_id], array_values($days_values)));
        $response['status'] = 3;
        $response["message"] = ["Saved correctly."];
    }
    echo json_encode($response);
    
    $conn->close();

} else {
    echo json_encode(["success" => false, "message" => "Insufficient data."]);
}
?>