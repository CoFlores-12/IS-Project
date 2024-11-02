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
                a.status,
                e.exam_name,
                ar.result
                FROM 
                    Applicant a
                JOIN 
                    Persons p ON a.person_id = p.person_id
                LEFT JOIN 
                    Applicant_result ar ON a.person_id = ar.identity_number
                LEFT JOIN 
                    Exams e ON ar.exam_code = e.exam_code;
                ";

    $result = $conn->execute_query($query);


    foreach ($result as $applicant) {
        $name = $applicant['first_name'];
        $mailAspirant = $applicant['personal_email'];
        $status = $applicant['status'];
        $affair = "Estatus de Examen de Admisión";
        $message = "Hola $name,<br><br>Tu estatus de examen de admisión es: <strong>$status</strong>.<br><br>Saludos,<br>Equipo de Admisiones.";
    
        $resultado = $mail->sendEmail(getenv('emailUser'), $mailAspirant, $affair, $message);
        echo $resultado . "<br>"; 
    }

?>

