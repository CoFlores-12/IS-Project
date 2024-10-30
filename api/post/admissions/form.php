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

            // 1. Validar el primer nombre y apellido
            if (empty($_POST['name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['name'])) {
                $errors['first_name'] = "El primer nombre solo debe contener letras y espacios.";
            }
        
            if (empty($_POST['lastName']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['lastName'])) {
                $errors['last_name'] = "El apellido solo debe contener letras y espacios.";
            }
        
            // 2. Validar identidad (Formato: 0801-2000-12345)
            if (empty($_POST['identity']) || !preg_match("/^\d{4}-\d{4}-\d{5}$/", $_POST['identity'])) {
                $errors['identity'] = "La identidad debe tener el formato 0801-2001-12345.";
            }
        
            // 3. Validar número de teléfono (Formato: 3XXX-XXXX, 8XXX-XXXX, 9XXX-XXXX)
            if (empty($_POST['phone']) || !preg_match("/^[389]\d{3}-\d{4}$/", $_POST['phone'])) {
                $errors['phone'] = "El número de teléfono debe comenzar con 3, 8 o 9 y tener el formato XXXX-XXXX.";
            }
        
            // 4. Validar correo electrónico
            if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "El correo electrónico no es válido.";
            }
        
            // 5. Validar selección de carrera principal y secundaria
            if (empty($_POST['mainCareer'])) {
                $errors['mainCareer'] = "Debe seleccionar una carrera principal.";
            }
            if (empty($_POST['secondaryCareer'])) {
                $errors['secondaryCareer'] = "Debe seleccionar una carrera secundaria.";
            }
        
            // 6. Validar selección de centro regional
            if (empty($_POST['regionalCenter'])) {
                $errors['regionalCenter'] = "Debe seleccionar un centro regional.";
            }

            if (!empty($errors)) {
                echo "<h3>Errores de Validación:</h3>";
                echo "<ul>";
                foreach ($errors as $field => $error) {
                    echo "<li><strong>$field</strong>: $error</li>";
                }
                echo "</ul>";
                echo '<a href="/">Regresar</a>';
                
                return;
            }

            return;
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
