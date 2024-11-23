<?php
$status = true;
$message = 'Error in server';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $status = false;
    $message = "Error de petición";
}

if (!isset($_FILES['certify']) || $_FILES['certify']['error'] !== UPLOAD_ERR_OK) {
    $status = false;
    $message =  "Archivo de certificado no enviado";
}

$maxFileSize = 2 * 1024 * 1024; // 2 MB
if ($_FILES['certify']['size'] > $maxFileSize) {
    $status = false;
    $message =  "El archivo es demasiado grande. el tamaño máximo es 2 MB.";
}

if (!$status) {
    echo json_encode([
        'status'=>$status,
        'message'=> $message
    ]);
    return;
}

require '../../../src/models/AsppirantModel.php';
include '../../../src/modules/database.php';
include '../../../src/modules/mails.php';


// Archivo a enviar
$fileTmpPath = $_FILES['certify']['tmp_name'];
$fileContent = file_get_contents($fileTmpPath);

$fileExtension = strtolower(pathinfo($_FILES['certify']['name'], PATHINFO_EXTENSION));

if (file_exists(__DIR__ . '../../../../.env')) {
    require __DIR__ . '../../../../vendor/autoload.php';
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../../')->load();
}

$aspirant = new Aspirant($_POST, $fileContent, $fileExtension);
$conn = (new Database())->getConnection();
if ($aspirant->save($conn)) {
    $status = true;
    $message =  "Datos guardados correctamente. Por favor! revise su correo electrónico";
    $aspirantData = $aspirant->toArray(); 
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
            <h1>Confirmación de Registro</h1>
        </div>
        <div class=\"content\">
            <p>Estimado(a) {$aspirant->firstName},</p>
            <p>Gracias por inscribirte en el proceso de admisión. Hemos recibido tus datos y a continuación te compartimos un resumen de tu registro:</p>
            <h3>Detalles de tu registro:</h3>
            <ul>";
                foreach ($aspirantData as $key => $value) {
                    $emailBody .= "<li><strong>" . ucfirst($key) . ":</strong> $value</li>";
                }
                $emailBody .= "
            </ul>
            <p>Por favor, conserva esta información para futuras referencias. Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
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

// Enviar el correo
$mailer = new Mails(getenv('emailUser'), getenv('emailPassword'));
$emailResult = $mailer->sendEmail(
    getenv('emailUser'),   // Correo del remitente
    $aspirant->email,      // Correo del destinatario
    'Confirmación de Registro', // Asunto del correo
    $emailBody             // Cuerpo del correo en formato HTML
);
} else {
    $errors = $aspirant->getErrors();
    $status = false;
    $message = "<h3>Errores en datos:</h3><ul>";
    foreach ($errors as $field => $error) {
        $message .= "<li><strong>$field</strong>: $error</li>"; 
    }
    $message .= "</ul>";
}
echo json_encode([
    'status'=>$status,
    'message'=> $message
]);
?>