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
    $emailBody = "<h3>Detalles de tu registro:</h3><ul>";
    foreach ($aspirantData as $key => $value) {
        $emailBody .= "<li><strong>$key:</strong> $value</li>";
    }
    $emailBody .= "</ul>";

    $mailer = new Mails(getenv('emailUser'), getenv('emailPassword'));
    $emailResult = $mailer->sendEmail(
        getenv('emailUser'),  
        $aspirant->email,      
        'Confirmación de Registro',
        $emailBody            
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