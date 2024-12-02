<?php
header('Content-Type: application/json');


require_once '../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);


$employeetid = $_SESSION['user']['employeenumber'];


$response = [];

$response['ID'] = $employeetid;

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = '
   SELECT 
    F.faculty_name, D.department_id
    FROM 
        Employees E
    JOIN 
        Departments D ON E.department_id = D.department_id
    JOIN 
        Careers C ON D.department_id = C.department_id
    JOIN 
        Faculty F ON C.faculty_id = F.faculty_id
    WHERE 
        E.employee_number = ?
    LIMIT 1;
';

$result = $conn->execute_query($sql, [$employeetid]);

$detail = $result->fetch_assoc();

$response["detail"] = $detail;

$response["departamentid"] = $response["detail"]["department_id"];
$response["facultyname"] = $response["detail"]["faculty_name"];

$departamentid = $response["detail"]["department_id"];

$sql = '
        SELECT 
        C.class_id,
        C.class_name,
        C.uv,
        C.class_code
    FROM 
        Classes C
    JOIN 
        ClassesXCareer CC ON C.class_id = CC.class_id
    JOIN 
        Careers CR ON CC.career_id = CR.career_id
    JOIN 
        Departments D ON CR.department_id = D.department_id
    WHERE 
        D.department_id = ?;
';

$result = $conn->execute_query($sql, [$departamentid]);

$classes = [];

while ($row = $result->fetch_assoc()) {
    $classes[] = $row;  
}

$response['clases'] = $classes;

$sql = '
        SELECT 
        E.employee_number, 
        E.institute_email, 
        P.first_name, 
        P.last_name, 
        D.department_name
    FROM 
        Employees E
    JOIN 
        Roles R ON E.role_id = R.role_id
    JOIN 
        Persons P ON E.person_id = P.person_id
    JOIN 
        Departments D ON E.department_id = D.department_id 
    WHERE 
        (R.type = "Teacher" OR R.type = "Department Head" OR R.type = "Coordinator") AND E.department_id = ?;    
    ';

$result = $conn->execute_query($sql, [$departamentid]);

$teachers = [];

while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;  
}

$response['teachers'] = $teachers;

$sql = '
        SELECT DISTINCT
            d.department_name AS department_name,
            f.faculty_name AS faculty_name,
            rc.center_name AS center_name,
            b.building_name AS building_name,
            cl.classroom_name AS classroom_name,
            cl.capacity AS classroom_capacity,
            cl.classroom_id AS classroom_id
        FROM 
            Departments d
        JOIN 
            Careers ca ON d.department_id = ca.department_id
        JOIN 
            Faculty f ON ca.faculty_id = f.faculty_id
        JOIN 
            CareersXRegionalCenter crc ON ca.career_id = crc.career_id
        JOIN 
            Regional_center rc ON crc.center_id = rc.center_id
        JOIN 
            Building b ON rc.center_id = b.center_id
        JOIN 
            Classroom cl ON b.building_id = cl.building_id
        WHERE 
            d.department_id = ?; 
    ';

$result = $conn->execute_query($sql, [$departamentid]);

$classroom = [];

while ($row = $result->fetch_assoc()) {
    $classroom[] = $row;  // Agregar cada fila al array
}

$response['classroom'] = $classroom;



if (!empty($classes) && !empty($teachers) ) {
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(['Message' => 'No data found']);
}


$conn->close();
exit;