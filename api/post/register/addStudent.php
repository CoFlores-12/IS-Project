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
        WHERE a.status = 'accepted'";

$result = $conn->execute_query($sql);

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

    $baseEmail = $firstInitial . $lastNameLower . '@unah.edu';
    $email = $baseEmail;

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

foreach ($result as $student) {
    $first_name = $student['first_name'];
    $personal_email = $student['personal_email'];
    $last_name = $student['last_name'];
    $person_id = $student['person_id'];

    $password = generatePassword();
    $instituteEmail = generateEmail($name, $lastName);
    $numberAccount = 20191030337;

    $passphrase = getenv('password');

    $sql = "INSERT INTO Students (account_number, person_id, password, institute_email) VALUES (?, ?, ENCRYPTBYPASSPHRASE(?, ?), ?)";

    $conn->execute_query($sql, [$numberAccount, $person_id, $passphrase, $password, $instituteEmail]);

    $affair = "Credenciales de usuario";
    $message = "Hola $first_name,<br><br>Tu Correo Institucional es: <strong>$instituteEmail</strong>.<br><br>Tu Contraseña del sistema es: <strong>$password</strong>.<br><br><br>Tu numero de cuenta es: <strong>$password</strong>.<br><br>Saludos,<br>Bienvenido/a.";

    $resultado = $mail->sendEmail(getenv('emailUser'), $personal_email, $affair, $message);

    echo $first_name . "<br>";
}

?>