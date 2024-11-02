<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
   
} 
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$name = $_POST['name'];
$lastName = $_POST['lastName'];
$identity = $_POST['identity'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$role = 'Teacher';

$sql = "INSERT INTO `Persons`(person_id,first_name,last_name,phone,personal_email ) VALUES (?,?,?,?,?);";
$conn->execute_query($sql, [$identity,$name,$lastName,$phone,$email]);

//TODO: generate email & password

function generatePassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}
function generateEmail($firstName, $lastName) {
    $firstInitial = strtolower(substr($firstName, 0, 1));
    $lastNameLower = strtolower($lastName);
    
    $email = $firstInitial . $lastNameLower . '@unah.edu';
    
    return $email;
}
$password = generatePassword();
$instituteEmail = generateEmail($name, $lastName);

$sql = "insert into `Administrators` (person_id, role, password, institute_email) values(?, ?, ?, ?)";
$conn->execute_query($sql, [$identity, $role, $password, $instituteEmail]);
//TODO: send email with a credential to the new user

echo 'User Created! <a href="#" onclick="history.back(); return false;">Go Back</a>'


?>
