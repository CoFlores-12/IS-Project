<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
    exit();
} 

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$classId = $data['classId'] ?? null;
$starttime = $data['starttime'] ?? null;
$endtime = $data['endtime'] ?? null;
$classroomId = $data['classroomId'] ?? null;
$teacherId = $data['teacherId'] ?? null;
$quotas = $data['quotas'] ?? null;
$days = $data['days'] ?? null;


if (!empty($classId) && !empty($starttime) && !empty($endtime) && !empty($classroomId) && !empty($teacherId) && !empty($quotas)) {


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
    
    if(!$is_available1){
        echo json_encode(["success" => false, "message" => "Classroom busy at this time."]);
    }elseif(!$is_available2){
        echo json_encode(["success" => false, "message" => "Teacher busy at this time-"]);
    }else{

        $sql = "INSERT INTO Section (class_id, hour_start, hour_end, classroom_id, quotas, employee_number) VALUES (?, ?, ?, ?, ?, ?)";
        $conn->execute_query($sql, [$classId, $starttime, $endtime, $classroomId,$quotas,$teacherId]);
        
        $section_id = $conn->insert_id;

        $days_array = explode(',', $days);


        $SQL = "INSERT INTO SectionDays (section_id, day) VALUES (?, ?)";

        foreach ($days_array as $day) {
            $conn->execute_query($SQL, [$section_id, $day]);
        }

        echo json_encode(["success" => true, "message" => "Saved correctly."]);

    }

    $conn->close();

} else {
    echo json_encode(["success" => false, "message" => "Insufficient data."]);
}
?>