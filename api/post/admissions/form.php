<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

if (!isset($_FILES['certify']) || $_FILES['certify']['error'] !== UPLOAD_ERR_OK) {
    echo "No file was uploaded or there was an error uploading the file.";
    return;
}

require '../../../src/models/AsppirantModel.php';
include '../../../src/modules/database.php';

$fileTmpPath = $_FILES['certify']['tmp_name'];
$fileData = file_get_contents($fileTmpPath);
$encodedFile = base64_encode($fileData);

// Enviar a imgbb usando cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.imgbb.com/1/upload?key=cee84319e470684665a483c6b90b9ce8");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => $encodedFile,
]);

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);

if (isset($responseData['success']) && $responseData['success']) {
    $imageUrl = $responseData['data']['url']; // URL de la imagen subida

    $aspirant = new Aspirant($_POST, $imageUrl);
    $conn = (new Database())->getConnection();

    if ($aspirant->save($conn)) {
        echo "Data entered successfully. <a href=\"/\">Go Back</a>";
    } else {
        // Muestra los errores de validación, si existen
        $errors = $aspirant->getErrors();
        echo "<h3>Validation Errors:</h3>";
        echo "<ul>";
        foreach ($errors as $field => $error) {
            echo "<li><strong>$field</strong>: $error</li>";
        }
        echo "</ul>";
        echo '<a href="/">Go Back</a>';
    }
} else {
    echo "There was an error uploading the file to imgbb.";
    echo '<a href="/">Go Back</a>';
}
?>