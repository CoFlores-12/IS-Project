<?php

$email = $_POST['email'];
$password = $_POST['password'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "CALL LoginStudent(?, ?, @is_authenticated, @out_id);";
$result = $conn->execute_query($sql, [$email, $password]);
$result = $conn->query("SELECT @is_authenticated AS is_authenticated, @out_id AS out_id");

$row = $result->fetch_assoc();
$is_authenticated = $row['is_authenticated'];
$role = $row['out_id'];

if ($is_authenticated) {
    session_start();
    $_SESSION['role'] = 'student';
    $_SESSION['route'] = 'student';
    echo json_encode(["route" => "/views/students/home/index.php"]);
} else {
    http_response_code(404);
    echo "invalid credentials";
}

