<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Parámetros POST recibidos:</h3>";
    echo "<pre>";
    print_r($_POST); 
    echo "</pre>";

    $data = "Parámetros POST recibidos:\n" . print_r($_POST, true) . "\n";

    if (!file_exists('../../../uploads')) {
        mkdir('../../../uploads', 0777, true); 
    }

    if (isset($_FILES['certify']) && $_FILES['certify']['error'] === UPLOAD_ERR_OK) {
        $uploadFile = '../../../uploads/' . basename($_FILES['certify']['name']);
        if (move_uploaded_file($_FILES['certify']['tmp_name'], $uploadFile)) {
            echo "El archivo se ha subido exitosamente: " . htmlspecialchars($uploadFile);
            $data .= "Archivo subido: " . htmlspecialchars($uploadFile) . "\n";
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "No se subió ningún archivo.";
    }
} else {
    echo "Por favor, envíe el formulario para ver los datos.";
}
?>
