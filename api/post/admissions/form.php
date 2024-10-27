<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Guardar Certificado
    if (!file_exists('../../../uploads')) {
        mkdir('../../../uploads', 0777, true); 
    }

    if (isset($_FILES['certify']) && $_FILES['certify']['error'] === UPLOAD_ERR_OK) {
        $originalFileName = $_FILES['certify']['name'];
        $uploadFile = '../../../uploads/' . basename($_FILES['certify']['name']);
        if (move_uploaded_file($_FILES['certify']['tmp_name'], $uploadFile)) {


            //TODO: Validar datos
            
            //Guardar Datos
            include '../../../src/modules/database.php';

            $conn = (new Database())->getConnection();

            $identityNumber = $_POST['identity'];
            $firstName = $_POST['name'];
            $lastName = $_POST['lastName'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $preferredCareer = $_POST['mainCareer'];
            $secondaryCareer = $_POST['secondaryCareer'];
            $regionalCenter = $_POST['regionalCenter'];

            $sql = "INSERT INTO Applicant (identity_number, first_name, last_name, phone, email, preferred_career, secondary_career, regional_center, document_photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $conn->execute_query($sql, [$identityNumber, $firstName, $lastName, $phone, $email, $preferredCareer, $secondaryCareer, $regionalCenter, $originalFileName]);


            //TODO: mejorar UI/UX
            echo 'Datos ingresados <a href="/">Regresar</a>';
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
