<?php
include '../../../src/modules/mails.php';
include '../../../src/modules/database.php';

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
}

$mail = new Mails(getenv('emailUser'), getenv('emailPassword'));

$conn = (new Database())->getConnection();

$query = "SELECT 
            p.first_name, 
            p.last_name, 
            p.personal_email,
            GROUP_CONCAT(DISTINCT e.exam_code ORDER BY e.exam_code ASC SEPARATOR ', ') AS exam_codes, 
            GROUP_CONCAT(DISTINCT ar.result_exam ORDER BY e.exam_code ASC SEPARATOR ', ') AS exam_results,  
            CASE 
                WHEN a.approved_pref = 1 AND a.approved_sec = 1 THEN CONCAT(c1.career_name, ', ', c2.career_name)
                WHEN a.approved_pref = 1 THEN c1.career_name  
                WHEN a.approved_sec = 1 THEN c2.career_name  
                ELSE NULL
            END AS approved_career,  
            a.email_sent AS email_status,
            a.applicant_id
        FROM 
            Applicant a
        JOIN 
            Persons p ON a.person_id = p.person_id
        LEFT JOIN 
            Applicant_result ar ON a.person_id = ar.identity_number  
        LEFT JOIN 
            Exams e ON ar.exam_code = e.exam_code  
        LEFT JOIN 
            Careers c1 ON a.preferend_career_id = c1.career_id  
        LEFT JOIN 
            Careers c2 ON a.secondary_career_id = c2.career_id  
        WHERE 
            a.email_sent = 0  
        GROUP BY 
            a.applicant_id, p.first_name, p.last_name, p.personal_email, a.email_sent;";

$result = $conn->execute_query($query);

foreach ($result as $applicant) {
    $firstName = $applicant['first_name'];
    $lastName = $applicant['last_name'];
    $email = $applicant['personal_email'];
    $examCodes = $applicant['exam_codes'];
    $examResults = $applicant['exam_results'];
    $approvedCareer = $applicant['approved_career'];

    $affair = "Resultados de Examen de Admisión";
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
                    <p>Estimado(a) $firstName $lastName,</p>
                    <p>Nos complace informarle los resultados de los exámenes que realizó:</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Código del Examen</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    $examCodesArray = explode(', ', $examCodes);
    $examResultsArray = explode(', ', $examResults);
    foreach ($examCodesArray as $index => $examCode) {
        $resultScore = $examResultsArray[$index] ?? 'N/A';
        $message .= "
                            <tr>
                                <td>$examCode</td>
                                <td>$resultScore</td>
                            </tr>";
    }

    $message .= "
                        </tbody>
                    </table>";

    if ($approvedCareer) {
        $careersArray = explode(', ', $approvedCareer);  
        $message .= "<p>Has sido aprobado para las siguientes carreras:</p><ul>";
        
        foreach ($careersArray as $career) {
            $message .= "<li><span class='checkmark'></span><strong>$career</strong></li>";
        }
        $message .= "</ul>";
    } else {
        $message .= "<p>Lo sentimos, no has sido aprobado para ninguna carrera.</p>";
    }

    $message .= "
                    <p>Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.</p>
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

    $resultado = $mail->sendEmail(getenv('emailUser'), $email, $affair, $message);

    if ($resultado === 'Mail sent successfully.') {
        $updateQuery = "UPDATE Applicant SET email_sent = 1 WHERE applicant_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $applicant['applicant_id']);
        $stmt->execute();
    }
}

echo json_encode($result);
?>
