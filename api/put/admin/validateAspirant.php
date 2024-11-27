<?php
include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();
require_once '../../../src/modules/mails.php'; 

$applicant_id = $_POST['applicant_id'];
$validate = $_POST['validate'];  
$razon = $_POST['razon'];  

if (empty($applicant_id) || !isset($validate)) {
    die('Datos incompletos o incorrectos.');
}

$conn->begin_transaction();

try {
    $obs_id = ($validate == 0) ? $razon : 12; 

    $updateValidationQuery = "
        UPDATE ValidatorxApplicant
        SET is_valid = ?, obs_id = ?
        WHERE applicant_id = ? 
        LIMIT 1
    ";
    $stmt = $conn->prepare($updateValidationQuery);
    $stmt->bind_param("iii", $validate, $obs_id, $applicant_id);
    $stmt->execute();

    if ($stmt->affected_rows <= 0) {
        throw new Exception("No se encontró el applicant o no se pudo actualizar.");
    }

    $applicantQuery = "SELECT p.first_name, p.personal_email FROM Applicant a INNER JOIN `Persons` p ON a.person_id = p.person_id WHERE a.applicant_id = ?";
    $stmt = $conn->prepare($applicantQuery);
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();

    if ($validate == 0) {
        $getCommentQuery = "SELECT comment FROM obsReviews WHERE obsReview_id = ?";
        $stmt = $conn->prepare($getCommentQuery);
        $stmt->bind_param("i", $razon);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

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
                    <h1>Corrección Requerida en tu Registro</h1>
                </div>
                <div class=\"content\">
                    <p>Estimado(a) {$applicant['first_name']},</p>
                    <p>Hemos revisado tu solicitud y hemos detectado algunos detalles que necesitan corrección. A continuación, te detallamos el comentario:</p>
                    <h3>Razón de la Invalidación:</h3>
                    <ul>
                        <li><strong>Comentario:</strong> {$comment['comment']}</li>
                    </ul>
                    <p>Por favor, realiza las correcciones indicadas y vuelve a enviar tu solicitud para ser revisada nuevamente.</p>
                    <p>Atentamente,</p>
                    <p><strong>Equipo de Admisiones</strong></p>
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
            'Corrección Requerida en tu Registro', 
            $emailBody             
        );

        if (!$emailResult) {
            throw new Exception("Error al enviar el correo al aspirante.");
        }
    }

    $conn->commit();

    echo json_encode([
        "status" => true,
        "message" => "El applicant ha sido actualizado correctamente y se envió el correo de corrección."
    ]);
} catch (Exception $e) {
    $conn->rollback();

    echo json_encode([
        "status" => false,
        "message" => "Error al actualizar el applicant: " . $e->getMessage()
    ]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
