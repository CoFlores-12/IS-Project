<?php
include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();
require_once '../../../src/modules/mails.php'; 

$request_id = $_POST['request_id'];
$value = $_POST['value'];  
$response = $_POST['response'];  

if (empty($request_id) || !isset($value)) {
    die('Datos incompletos o incorrectos.');
}

$conn->begin_transaction();

try {
    $updateValidationQuery = "
        UPDATE Requests
        SET status = ?, response = ?
        WHERE request_id = ? 
    ";
    $stmt = $conn->prepare($updateValidationQuery);
    $stmt->bind_param("isi", $value, $response, $request_id);
    $stmt->execute();

    if ($stmt->affected_rows <= 0) {
        throw new Exception("No se encontró la solicitud o no se pudo actualizar.");
    }

    $applicantQuery = "SELECT 
        p.first_name, 
        p.personal_email, 
        rt.title, 
        r.career_change_id, 
        r.campus_change_id, 
        r.request_type_id,
        s.account_number,
        p.person_id
    FROM Requests r 
    INNER JOIN `RequestTypes` rt ON r.request_type_id = rt.request_type_id
    INNER JOIN `Students` s ON r.student_id = s.account_number 
    INNER JOIN `Persons` p ON s.person_id = p.person_id
    WHERE r.request_id = ?";
    $stmt = $conn->prepare($applicantQuery);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();
    $dict = intval($value) === 0 ? 'Rechazada' : "Aprobada";
        $emailBody = "
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
                ul {
                    padding-left: 20px;
                }
                ul li {
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class=\"email-container\">
                <div class=\"header\">
                    <h1>Dictamen de solicitud</h1>
                </div>
                <div class=\"content\">
                    <p>Estimado(a) {$applicant['first_name']},</p>
                    <p>Hemos revisado tu solicitud de {$applicant['title']} y ha sido {$dict} </p>
                    <p>Por favor, revisar en la plataforma para mas detalles.</p>
                </div>
                <div class=\"footer\">
                    <p>Este correo es generado automáticamente. Por favor, no responda a este mensaje.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $mailer = new Mails(getenv('emailUser'), getenv('emailPassword'));
        $emailResult = $mailer->sendEmail(
            getenv('emailUser'),   
            $applicant['personal_email'],    
            'Dictamen de tu solicitud', 
            $emailBody             
        );

        if (!$emailResult) {
            throw new Exception("Error al enviar el correo al estudiante.");
        }
        if (intval($value) === 1) {
            if (intval($applicant['request_type_id']) === 3) {
                $sql = "UPDATE Students SET career_id = ? where account_number = ?";
                $result = $conn->execute_query($sql,[$applicant['career_change_id'], $applicant['account_number']]);
                if (!$result) {
                    throw new Exception("Error al cambiar al estudiante de carrera.");
                }
                
            } elseif (intval($applicant['request_type_id']) === 4) {
                $sql = "UPDATE Persons SET center_id = ? where person_id = ?";
                $result = $conn->execute_query($sql,[$applicant['campus_change_id'], $applicant['person_id']]);
                if (!$result) {
                    throw new Exception("Error al cambiar al estudiante de centro.");
                }
            }
        }

    $conn->commit();

    echo json_encode([
        "status" => true,
        "message" => "La solicitud fue respondida correctamente"
    ]);
} catch (Exception $e) {
    $conn->rollback();

    echo json_encode([
        "status" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
