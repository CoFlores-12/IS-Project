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
            sa.description AS status,
            GROUP_CONCAT(e.exam_name SEPARATOR ', ') AS exam_names,
            GROUP_CONCAT(ar.result_exam SEPARATOR ', ') AS exam_results
        FROM 
            Applicant a
        JOIN 
            Persons p ON a.person_id = p.person_id
        LEFT JOIN 
            Applicant_result ar ON a.person_id = ar.identity_number
        LEFT JOIN 
            Exams e ON ar.exam_code = e.exam_code
        LEFT JOIN 
            StatusApplicant sa ON a.status_id = sa.status_id
        GROUP BY 
            p.person_id, p.first_name, p.last_name, p.personal_email, sa.description;";

$result = $conn->execute_query($query);

foreach ($result as $applicant) {
    $firstName = $applicant['first_name'];
    $lastName = $applicant['last_name'];
    $email = $applicant['personal_email'];
    $examNames = $applicant['exam_names'];
    $examResults = $applicant['exam_results'];

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
                                <th>Nombre del Examen</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    // Separar los datos de exámenes
    $examNamesArray = explode(', ', $examNames);
    $examResultsArray = explode(', ', $examResults);
    foreach ($examNamesArray as $index => $examName) {
        $resultScore = $examResultsArray[$index] ?? 'N/A';
        $message .= "
                            <tr>
                                <td>$examName</td>
                                <td>$resultScore</td>
                            </tr>";
    }
    
    $message .= "
                        </tbody>
                    </table>
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
}

echo json_encode($result);
?>
