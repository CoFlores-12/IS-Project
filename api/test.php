<?php
date_default_timezone_set('America/Tegucigalpa');
$currentDate = new DateTime();

print_r($currentDate);

$host = '185.27.134.98'; // Usa la IP si DNS falla
$username = 'if0_37535915';
$password = 'Qwerty2024FTP';
$database = 'if0_37535915_isproject';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>