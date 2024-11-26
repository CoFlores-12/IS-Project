<?php
//validate method of request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "status"=> false,
        "message"=> "Método no disponible"
    ]);
   
} 

$name = $_POST['name'];
$lastName = $_POST['lastName'];
$identity = str_replace("-", "", $_POST['identity']);
$phone = str_replace("-", "", $_POST['identity']);
$email = $_POST['email'];
$role = 5;



include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$sql = "INSERT IGNORE INTO `Persons`(person_id,first_name,last_name,phone,personal_email ) VALUES (?,?,?,?,?);";
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
        
        $instituteEmail = $firstInitial . $lastNameLower . 'validator@unah.hn';
        $i++;
    } while (emailExists($instituteEmail));
    
    return $instituteEmail;
}

$password = generatePassword();
$instituteEmail = generateEmail($name, $lastName);

$sql = "CALL `CreateAdministrator`(?, 6, ?, ?, NULL);";
$conn->execute_query($sql, [$identity, $password, $instituteEmail]);

include '../../../src/modules/mails.php';
$affair = "User Created";
$message = "Hi $name,<br><br>Your institute email is: <strong>$instituteEmail</strong>.<br><br>your password is: <strong>$password</strong>.<br><br>welcome.";
if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
 }

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));
$message = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #176b87;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #555;
        }
        .button {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #176b87;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class=\"email-container\">
        <div class=\"header\">
            <h1>Bienvenido a la Plataforma Universitaria</h1>
        </div>
        <div class=\"content\">
            <p>Estimado(a) $name $lastName,</p>
            <p>Nos complace informarle que se ha creado su cuenta en nuestra plataforma universitaria. A continuación, encontrará sus credenciales de acceso:</p>
            <ul>
                <li><strong>Usuario:</strong> $instituteEmail</li>
                <li><strong>Contraseña:</strong> $password</li>
            </ul>
            <p>Por favor, haga clic en el botón a continuación para acceder a la plataforma:</p>
            <a href=\"https://is-project-main.up.railway.app/\" class=\"button\">Acceder a la Plataforma</a>
            <p>Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.</p>
            <p>Atentamente,</p>
            <p><strong>Equipo de Soporte</strong></p>
        </div>
        <div class=\"footer\">
            <p>Este correo es generado automáticamente. Por favor, no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>
";

$resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);

$conn->close();
echo json_encode([
    "status"=> true,
    "message"=> "Credenciales enviadas al correo"
])
?>
