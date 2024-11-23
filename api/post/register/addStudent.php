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
    $csvContent = $data['csvData']; 

    $rows = explode("\n", $csvContent);
    $parsedData = [];
    $headers = [];

    if (!empty($rows[0])) {
        $headers = str_getcsv($rows[0]);  
    }

    foreach (array_slice($rows, 1) as $row) {
        if (!empty($row)) {
            $line = str_getcsv($row);
            if (count($line) == count($headers)) {
                $parsedData[] = array_combine($headers, $line);
            }
        }
    }

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

        $conn->execute_query($sql, [$numberAccount, $person_id, $passphrase, $password, $instituteEmail, $photosJson, $carrer]);


        $affair = "Credenciales de usuario";
        $message = `
        <p>Saludos, <strong>{$first_name}</strong> <strong>{$last_name}</strong>,</p>

            <p>A continuación te mostramos los resultados de tus exámenes:</p>
            
            <ol>
                <li>Carrera principal: <strong>{$approved_pref}</strong></li>
                <li>Carrera secundaria: <strong>{$approved_sec}</strong></li>
            </ol>
        `;
    
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
