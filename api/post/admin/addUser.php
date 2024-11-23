<?php
//validate method of request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h3>Request invalid</h3>";
   
} 

$name = $_POST['name'];
$lastName = $_POST['lastName'];
$identity = str_replace("-", "", $_POST['identity']);
$phone = str_replace("-", "", $_POST['identity']);
$email = $_POST['email'];
$departament = $_POST['departament'];
$role = 5;

echo json_encode([
    "status"=> true,
    "message"=> "Credenciales enviadas al correo"
]);

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "INSERT INTO `Persons`(person_id,first_name,last_name,phone,personal_email ) VALUES (?,?,?,?,?);";
$conn->execute_query($sql, [$identity,$name,$lastName,$phone,$email]);

function generatePassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}

function emailExists($email) {
    global $conn;
    $sql = "SELECT COUNT(*) AS count FROM Employees WHERE institute_email = ?";
    $result = $conn->execute_query($sql, [$email]);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; 
    }    
    return false; 
}
function generateEmail($firstName, $lastName) {
    $i = 1;
    do {
        $firstInitial = strtolower(substr($firstName, 0, $i));
        $lastNameLower = strtolower($lastName);
        
        $instituteEmail = $firstInitial . $lastNameLower . '@unah.edu';
        $i++;
    } while (emailExists($instituteEmail));
    
    return $instituteEmail;
}

$password = generatePassword();
$instituteEmail = generateEmail($name, $lastName);

$sql = "CALL `CreateAdministrator`(?, 5, ?, ?, ?);";
$conn->execute_query($sql, [$identity, $password, $instituteEmail, $departament]);

include '../../../src/modules/mails.php';
$affair = "User Created";
$message = "Hi $name,<br><br>Your institute email is: <strong>$instituteEmail</strong>.<br><br>your password is: <strong>$password</strong>.<br><br>welcome.";
if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
 }

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

$resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);

$conn->close();
echo json_encode([
    "status"=> true,
    "message"=> "Credenciales enviadas al correo"
])
?>
