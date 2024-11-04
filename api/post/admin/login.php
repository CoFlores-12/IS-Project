<?php

$email = $_POST['email'];
$password = $_POST['password'];

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "CALL LoginAdministrator(?, ?, @is_authenticated, @out_role);";

$result = $conn->execute_query($sql, [$email, $password]);
$result = $conn->query("SELECT @is_authenticated AS is_authenticated, @out_role AS role");

$row = $result->fetch_assoc();
$is_authenticated = $row['is_authenticated'];
$role = $row['role'];

if ($is_authenticated) {
    echo json_encode(["route" => "/views/admin/".$role."/home/index.php"]);
} else {
    http_response_code(404);
    echo json_encode(["message" => "invalid credentials"]);
}

