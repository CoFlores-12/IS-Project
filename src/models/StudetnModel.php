<?php

include '../modules/database.php';
include '../modules/mails.php';

/**
 * Initializes database connection and loads environment variables.
 */
$conn = (new Database())->getConnection();

if (file_exists(__DIR__ . '../../.env')) {
    require __DIR__ . '../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../')->load();
}

/**
 * Creates an instance of the Mails class with SMTP credentials from environment variables.
 */
$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

/**
 * SQL query to retrieve distinct applicants who have been admitted, including their personal email.
 */
$sql = "SELECT DISTINCT p.person_id, p.first_name, p.last_name, p.personal_email
        FROM Applicant a
        JOIN Persons p ON a.person_id = p.person_id
        WHERE a.status = 'Admitted'";

$result = $conn->execute_query($sql);

/**
 * Generates a random password consisting of 8 alphanumeric characters.
 *
 * @return string Generated password.
 */
function generatePassword() {
    $length = 8;
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}

/**
 * Generates an institutional email based on the applicant's first name and last name.
 *
 * @param string $firstName Applicant's first name.
 * @param string $lastName Applicant's last name.
 * @return string Generated institutional email.
 */
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

/**
 * Checks if a generated email already exists in the Students table.
 *
 * @param mysqli $conn Database connection object.
 * @param string $email Email to check for existence.
 * @return bool True if the email exists, false otherwise.
 */
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

/**
 * Loops through each admitted applicant and:
 * - Generates a password and institutional email.
 * - Inserts a new record in the Students table with the generated credentials.
 * - Sends an email to the applicant with their login credentials.
 */
foreach ($result as $student) {
    $first_name = $student['first_name'];
    $personal_email = $student['personal_email'];
    $last_name = $student['last_name'];
    $person_id = $student['person_id'];

    $password = generatePassword();
    $instituteEmail = generateEmail($first_name, $last_name);
    $numberAccount = 20191030337;

    $passphrase = getenv('password');

    $sql = "INSERT INTO Students (account_number, person_id, password, institute_email) VALUES (?, ?, AES_ENCRYPT(?, ?), ?)";

    $conn->execute_query($sql, [$numberAccount, $person_id, $passphrase, $password, $instituteEmail]);

    $affair = "Credenciales de usuario";
    $message = "Hola $first_name,<br><br>Tu Correo Institucional es: <strong>$instituteEmail</strong>.<br><br>Tu Contraseña del sistema es: <strong>$password</strong>.<br><br>Tu numero de cuenta es: <strong>$password</strong>.<br><br>Saludos,<br>Bienvenido/a.";

    $resultado = $mail->sendEmail(getenv('emailUser'), $personal_email, $affair, $message);
}
?>
