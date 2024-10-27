<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
   
} 

echo "<h3>Parámetros POST recibidos:</h3>";
    echo "<pre>";
    print_r($_POST); 
    echo "</pre>";
    
//get data from POST
$name = $_POST['name'];
$lastName = $_POST['lastName'];
$identity = $_POST['identity'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$role = $_POST['role'];
$employeeNumber = $_POST['employeeNumber'];

//TODO: generate user_id, email & password

$servername = "localhost";
$username = "usuario";
$password_db = "contraseña";
$dbname = "nombre_base_datos";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO User (user_id, identity_number, first_name, last_name, personal_email, institutional_email, phone, role, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


//TODO: execute query

//TODO: send email with a credential to the new user

//TODO: send feedback to the user


?>
