<?php

include '../../../src/modules/database.php';
include '../../../src/modules/mails.php';

$conn = (new Database())->getConnection();

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
}

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

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

    $baseEmail = $firstInitial . $lastNameLower . '@unah.hn';
    $email = $baseEmail;

    $conn = (new Database())->getConnection();

    if (emailExists($conn, $email)) {
        $firstInitials = strtolower(substr($firstName, 0, 2));
        $email = $firstInitials . $lastNameLower . '@unah.hn';
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
    
    $sql = "SELECT indicator FROM Periods WHERE year=".$year;

    $result = $conn->execute_query($sql);

    $row = $result->fetch_assoc();
    $indicator = $row['indicator'];
  
    $years = str_pad($year, 4, "0", STR_PAD_LEFT);       

    $processAdmission = "10".str_pad($indicator, 1, "0", STR_PAD_LEFT); 

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

$sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt')) AS phraseEncrypt FROM Config WHERE config_id = 1";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $passphrase = $row['phraseEncrypt'];  
}

$data = json_decode(file_get_contents('php://input'), true); 



if (isset($data['csvData'])) {
    
    

// Contenido del CSV
$csvContent = $data['csvData'];

// Dividimos el contenido en filas
$rows = explode("\n", $csvContent);
$parsedData = [];
$headers = [];

// Verificamos que haya al menos una fila para los encabezados
if (!empty($rows[0])) {
    // Procesamos los encabezados
    $headers = str_getcsv($rows[0]);
}

// Procesamos las filas, pero ignoramos la última (que es donde está el hash)
for ($i = 1; $i < count($rows) - 1; $i++) {
    $row = $rows[$i];
    if (!empty($row)) {
        $line = str_getcsv($row);
        if (count($line) === count($headers)) {
            $parsedData[] = array_combine($headers, $line);
        }
    }
}

// Extraemos y verificamos el hash de la última fila
$lastRow = end($rows);
$lastRowArray = str_getcsv($lastRow);

// Verificamos que la última fila contenga el hash
$receivedHash = null;
if (!empty($lastRowArray) && $lastRowArray[0] === '#hash') {
    $receivedHash = $lastRowArray[1];
}

// Calculamos el hash de los datos procesados
$dataString = json_encode($parsedData, JSON_UNESCAPED_UNICODE);

// Verificamos la codificación y convertimos a UTF-8 si es necesario
if (mb_detect_encoding($dataString, 'UTF-8', true) === false) {
    $dataString = mb_convert_encoding($dataString, 'UTF-8');
}

$calculatedHash = hash('sha256', $dataString);

/*
if ($receivedHash !== $calculatedHash) {
    echo json_encode(["success" => false, "message" => "Data integrity check failed. The hash does not match."]);
    exit;
}
*/

    foreach ($parsedData as $student) {
        $person_id = $student['person_id'];
        $first_name = $student['first_name'];
        $last_name = $student['last_name'];
        $personal_email = $student['personal_email'];

        $preferend_career_id = $student['preferend_career_id'];
        $secondary_career_id = $student['secondary_career_id'];
        $approved_pref = $student['approved_pref'];
        $approved_sec = $student['approved_sec'];

        if ($approved_pref == 1 && $approved_sec == 1) {
            $carrer = $preferend_career_id;
        } elseif ($approved_pref == 1) {
            $carrer = $preferend_career_id;
        } elseif ($approved_sec == 1) {
            $carrer = $secondary_career_id;
        } 
                  
        $password = generatePassword();
        $instituteEmail = generateEmail($first_name, $last_name);
        $numberAccount = generateAccountNumber();

        $photosArray = ["default.jpg"]; 
        $photosJson = json_encode($photosArray);

        $sql = "INSERT INTO Students(account_number, person_id, password, institute_email, photos, career_id) VALUES (?, ?, AES_ENCRYPT(?, ?), ?, ?, ?)";

        $conn->execute_query($sql, [$numberAccount, $person_id, $password, $passphrase, $instituteEmail, $photosJson, $carrer]);              

        $affair = "Credenciales de usuario";
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
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #176b87;
                        color: white;
                    }
                    ul {
                        list-style-type: none;
                        padding: 0;
                    }
                    li {
                        margin: 10px 0;
                        display: flex;
                        align-items: center;
                    }
                    .checkmark {
                        width: 16px;
                        height: 16px;
                        background-color: #28a745;
                        border-radius: 50%;
                        margin-right: 10px;
                    }
                    .crossmark {
                        width: 16px;
                        height: 16px;
                        background-color: #dc3545;
                        border-radius: 50%;
                        margin-right: 10px;
                    }
                </style>
            </head>
            <body>
                <div class=\"email-container\">
                    <div class=\"header\">
                        <h1>Resultados de tu Examen de Admisión</h1>
                    </div>
                    <div class=\"content\">
                        <p>Estimado(a) <strong> $first_name $last_name</strong>,</p>
                        <p>Nos complace informarte haz logrado ingresar en nuestra Universidad. A continuación, los detalles de tu Usuario:</p>
                        
                        <ul>
                            <li><strong>Correo Institucional:</strong>  $instituteEmail</li>
                            <li><strong>Contraseña:</strong> $password</li>
                        </ul>

                        <p>A continuación, te mostramos la carreras en la que has aprobado:</p>
                        <p> $carrer </p>
                        <p>Te deseamos mucho éxito en tu camino académico.</p>
                    </div>
                    <div class=\"footer\">
                        <p>Este correo es generado automáticamente. Por favor, no respondas a este mensaje.</p>
                    </div>
                </div>
            </body>
            </html>


        ";
    
        $resultado = $mail->sendEmail(getenv('emailUser'), $personal_email, $affair, $message);
    }

    echo json_encode([
        "success" => true,
        "message" => "CSV processed successfully.",
        "data" => $parsedData
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No CSV data received."]);
}
?>
