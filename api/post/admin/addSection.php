<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
    exit();
} 

include '../../../src/modules/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$classId = $data['classId'] ?? null;
$starttime = $data['starttime'] ?? null;
$endtime = $data['endtime'] ?? null;
$classroomId = $data['classroomId'] ?? null;
$teacherId = $data['teacherId'] ?? null;
$quotas = $data['quotas'] ?? null;
$days = $data['days'] ?? null;
$flag = $data['flag'] ?? null;
$csvContent = $data['csvData'] ?? null;  



if($flag == "manual"){

    echo json_encode(addSection($classId, $starttime, $endtime, $classroomId, $teacherId, $quotas, $days));

}elseif($flag == "archive"){

    $result = [];

    if (isset($data['csvData'])) {
        $rows = explode("\n", $csvContent);
        $parsedData = [];
        $headers = [];
        if (!empty($rows[0])) {
            $headers = str_getcsv($rows[0]);  
        }

        foreach (array_slice($rows, 1) as $row) {
            if (!empty($row)) {
                $line = str_getcsv($row);
                if (count($line) == count($headers)) {
                    $parsedData[] = array_combine($headers, $line);
                }
            }
        }

        $index = 1;

        foreach($parsedData as $section){
            $classId = $section['class_id'];
            $starttime = $section['hour_start'];
            $endtime = $section['hour_end'];
            $classroomId = $section['classroom_id'];
            $teacherId = $section['employee_number'];
            $quotas = $section['quotas'];
            $days = $section['days'];
    
            $result[$index] = ["line" => $index, "status" => addSection($classId, $starttime, $endtime, $classroomId, $teacherId, $quotas, $days)];
            $index++;
        }
    }

    echo json_encode($result);
}

function addSection($classId, $starttime, $endtime, $classroomId, $teacherId, $quotas, $days){
    $conn = (new Database())->getConnection();

    $response = [];

    if (!empty($classId) && !empty($starttime) && !empty($endtime) && !empty($classroomId) && !empty($teacherId) && !empty($quotas) && !empty($days)) {
    
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


        $sql = "SELECT capacity FROM Classroom WHERE classroom_id = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $classroomId);  // Asegúrate de que $quotas sea un valor numérico válido
        $stmt->execute(); 
        $result = $stmt->get_result(); 
                

        if ($row = $result->fetch_assoc()) {
            $capacity = $row['capacity']; 
            if($quotas > $capacity or $quotas < 10){
                $is_available3 = true;
            }else{
                $is_available3 = false;
            }
        } else {
            $is_available3 = false;
        }

        if(!$is_available1 and !$is_available2 ){
            $response['status'] = 0;
        }elseif(!$is_available1){
            $response['status'] = 1;
        }elseif(!$is_available2){
            $response['status'] = 2;
        }elseif($is_available3){
            $response['status'] = 3;
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
    
            $stmt = $conn->prepare($SQL);
    
            $stmt->bind_param(
                "iiiiiii", 
                $section_id,
                $days_values['Mon'],
                $days_values['Tue'],
                $days_values['Wed'],
                $days_values['Thu'],
                $days_values['Fri'],
                $days_values['Sat']
            );
    
            $stmt->execute();
            $response['status'] = "success";
          
        } 
        $conn->close(); 
        return $response;   
    
    } else {
        $response = [];
        $response['status'] = "data is missing";
        return $response;
    }

}

?>