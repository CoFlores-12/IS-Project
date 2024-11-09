<?php

$email = $_POST['email'];
$password = $_POST['password'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "CALL LoginAdministrator(?, ?, @is_authenticated, @out_role, @out_route, @out_employee_number);";
$result = $conn->execute_query($sql, [$email, $password]);
$result = $conn->query("SELECT @is_authenticated AS is_authenticated, @out_role AS role, @out_route as route, @out_employee_number as departmentid ");

$row = $result->fetch_assoc();
$is_authenticated = $row['is_authenticated'];
$role = $row['role'];
$route = $row['route'];
$departmentid = $row['departmentid'];

if ($is_authenticated) {
    session_start();
    $_SESSION['role'] = $role;
    $_SESSION['route'] = $route;
    $_SESSION['departmentid'] = $departmentid;
    echo json_encode(["route" => "/views/admin/".$route."/home/index.php"]);
} else {
    http_response_code(404);
    echo "invalid credentials";
}

