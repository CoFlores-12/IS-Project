<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

if (!file_exists('../../../uploads')) {
    mkdir('../../../uploads', 0777, true); 
}

if (!isset($_FILES['certify']) || $_FILES['certify']['error'] !== UPLOAD_ERR_OK) {
    echo "No file was uploaded or there was an error uploading the file.";
    return;
}

require '../../../src/models/AsppirantModel.php';
include '../../../src/modules/database.php';

$aspirant = new Aspirant($_POST, $_FILES['certify']);


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

?>
