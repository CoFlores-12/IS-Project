<?php
header("Content-Type: application/json");
include '../../../src/modules/database.php';
include '../../../src/modules/mails.php';
$conn = (new Database())->getConnection();
session_start();

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
}

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

$idStudent = $_SESSION['user']['student_id'] ?? null;
$idEmployee = $_SESSION['user']['employeenumber'] ?? null;

$response = [];

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "No valid identifier found in session."
    ]);
    exit;
}

$sender_id = $idStudent ?? $idEmployee;

$input = json_decode(file_get_contents("php://input"), true);

$receiver_id = $input['receiver_id'];


$sql = "SELECT * FROM contact_requests WHERE sender_id = ? AND receiver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response[] = ["estatus" => false,  "error" => "solicitud ya enviada"];
    } else {

        $sql = "SELECT p.first_name, p.last_name, s.account_number 
        FROM Students s 
        JOIN Persons p ON p.person_id = s.person_id 
        WHERE s.account_number = ?;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sender_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $account_number_sender_id = $row['account_number'];
        } 



        $sql1 = "SELECT p.first_name, p.last_name, p.personal_email
        FROM Students s 
        JOIN Persons p ON p.person_id = s.person_id 
        WHERE s.account_number = ?;";

        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $receiver_id);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($row = $result1->fetch_assoc()) {
            $firstName1 = $row['first_name'];
            $lastName1 = $row['last_name'];
            $email = $row['personal_email'];
        } 

    $affair = "Nueva Solicitud de contacto";
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
                <h1>Nueva solicitud de contacto</h1>
            </div>
            <div class=\"content\">
                <p>Estimado(a) <strong>$firstName1 $lastName1</strong>,</p>
                <p>Ha recibido una nueva solicitud de contacto. A continuacón, se muestran los detalles:</p>

                <div class=\"info-list\">
                    <p><strong>Nombre del Remitente:</strong> $firstName $lastName</p>
                    <p><strong>Número de Cuenta:</strong> $account_number_sender_id</p>
                </div>
                <p>Por favor, revise su bandeja de solicitudes pendientes para aceptar o rechazar esta solicitud.</p>
                <p>Atentamente,</p>
                <p><strong>Equipo de Soporte</strong></p>
                <div class=\"footer\">
                    <p>Este correo es generado automaticamente. Por favor, no responda a este mensaje.</p>
                </div>
            </div>
            </body>
            </html>
        ";

        $resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);

        $sql = "INSERT INTO contact_requests (sender_id, receiver_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $sender_id, $receiver_id);

        if ($stmt->execute()) {
            $response[] = ["estatus" => true,  "error" => "solicitud enviada"];
        } else {
            $response[] = ["estatus" => false,  "error" => "algo salio mal"];
        }
    }

echo json_encode([$response]);
exit;