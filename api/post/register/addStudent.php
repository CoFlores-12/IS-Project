<?php

include '../../../src/modules/database.php';
include '../../../src/modules/mails.php';

$conn = (new Database())->getConnection();

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
 }

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));


$sql = "SELECT DISTINCT p.person_id, p.first_name, p.last_name, p.personal_email
        FROM Applicant a
        JOIN Persons p ON a.person_id = p.person_id
        WHERE a.status_id = 1";

$result = $conn->execute_query($sql);

function generatePassword() {
    $length = 8;
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

    $baseEmail = $firstInitial . $lastNameLower . '@unah.edu';
    $email = $baseEmail;

    $conn = (new Database())->getConnection();

    if (emailExists($conn, $email)) {
        $firstInitials = strtolower(substr($firstName, 0, 2));
        $email = $firstInitials . $lastNameLower . '@unah.edu';
        return $email;
    }

    return $email;
    
}

function emailExists($conn, $email) {
    $email = $conn->real_escape_string($email);
    $sql = "SELECT COUNT(*) AS count FROM Students WHERE institute_email = '$email'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0; 
    }    
    return false; 
}

function generateAccountNumber() {

    $conn = (new Database())->getConnection();

    $year = date("Y");        
    $proceso = 103;       
  
  
    $years = str_pad($year, 4, "0", STR_PAD_LEFT);       

    $processAdmission = str_pad($proceso, 3, "0", STR_PAD_LEFT); 

    $query = "SELECT COUNT(*) AS students 
                FROM Students 
                WHERE SUBSTRING(account_number, 1, 4) = ? 
                AND SUBSTRING(account_number, 5, 3) = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $years, $processAdmission);
    $stmt->execute();
    $stmt->bind_result($students);
    $stmt->fetch();
    $stmt->close();

    $newSequential = str_pad(($students + 1), 4, "0", STR_PAD_LEFT);

    $numberAccount = $years . $processAdmission . $newSequential;

    return $numberAccount;
}

    
foreach ($result as $student) {
    $first_name = $student['first_name'];
    $personal_email = $student['personal_email'];
    $last_name = $student['last_name'];
    $person_id = $student['person_id'];

    $password = generatePassword();
    $instituteEmail = generateEmail($first_name, $last_name);
    $numberAccount = generateAccountNumber();

    $passphrase = getenv('password');

    $sql = "INSERT INTO Students (account_number, person_id, password, institute_email) VALUES (?, ?, AES_ENCRYPT(?, ?), ?)";

    $conn->execute_query($sql, [$numberAccount, $person_id, $passphrase, $password, $instituteEmail]);

    $affair = "Credenciales de usuario";
    $message = "Hola $first_name,<br><br>Tu Correo Institucional es: <strong>$instituteEmail</strong>.<br><br>Tu Contraseña del sistema es: <strong>$password</strong>.<br><br><br>Tu numero de cuenta es: <strong>$password</strong>.<br><br>Saludos,<br>Bienvenido/a.";

    $resultado = $mail->sendEmail(getenv('emailUser'), $personal_email, $affair, $message);

}

?>