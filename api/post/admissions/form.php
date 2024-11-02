<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}
//Guardar Certificado
if (!file_exists('../../../uploads')) {
    mkdir('../../../uploads', 0777, true); 
}

if (isset($_FILES['certify']) && $_FILES['certify']['error'] === UPLOAD_ERR_OK) {
    $originalFileName = $_FILES['certify']['name'];

    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    $newFileName = 'certify_' . str_replace("-", "", $_POST['identity']) . '.' . $fileExtension;

    $uploadFile = '../../../uploads/' . $newFileName;
    if (move_uploaded_file($_FILES['certify']['tmp_name'], $uploadFile)) {

        // 1. Validar el primer nombre y apellido
        if (empty($_POST['name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['name'])) {
            $errors['first_name'] = "The first name should contain only letters and spaces.";
        }
    
        if (empty($_POST['lastName']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['lastName'])) {
            $errors['last_name'] = "The last name should contain only letters and spaces.";
        }
    
        // 2. Validar identidad (Formato: 0801-2000-12345)
        if (empty($_POST['identity']) || !preg_match("/^\d{4}-\d{4}-\d{5}$/", $_POST['identity'])) {
            $errors['identity'] = "The identity must have the format XXXX-XXXX-XXXXX.";
        }
    
        // 3. Validar número de teléfono (Formato: 3XXX-XXXX, 8XXX-XXXX, 9XXX-XXXX)
        if (empty($_POST['phone']) || !preg_match("/^[389]\d{3}-\d{4}$/", $_POST['phone'])) {
            $errors['phone'] = "The phone number must start with 3, 8, or 9 and follow the format XXXX-XXXX.";
        }
    
        // 4. Validar correo electrónico
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "The email address is not valid.";
        }
    
        // 5. Validar selección de carrera principal y secundaria
        if (empty($_POST['mainCareer'])) {
            $errors['mainCareer'] = "You must select a main career.";
        }
        if (empty($_POST['secondaryCareer'])) {
            $errors['secondaryCareer'] = "You must select a secondary career.";
        }
    
        // 6. Validar selección de centro regional
        if (empty($_POST['regionalCenter'])) {
            $errors['regionalCenter'] = "You must select a regional center.";
        }

        if (!empty($errors)) {
            echo "<h3>Validation Errors:</h3>";
            echo "<ul>";
            foreach ($errors as $field => $error) {
                echo "<li><strong>$field</strong>: $error</li>";
            }
            echo "</ul>";
            echo '<a href="/">Go Back</a>';
            
            return;
        }

        include '../../../src/modules/database.php';

        $conn = (new Database())->getConnection();

        $identityNumber =str_replace("-", "", $_POST['identity']) ;
        $firstName = $_POST['name'];
        $lastName = $_POST['lastName'];
        $phone = str_replace("-", "", $_POST['phone']);
        $email = $_POST['email'];
        $preferredCareer = $_POST['mainCareer'];
        $secondaryCareer = $_POST['secondaryCareer'];
        $regionalCenter = $_POST['regionalCenter'];

        
        try {
            $sql = "INSERT IGNORE INTO `Persons`(person_id,first_name,last_name,phone,personal_email,center_id ) VALUES (?,?,?,?,?,?);";
            $conn->execute_query($sql, [$identityNumber,$firstName,$lastName,$phone,$email,$regionalCenter]);
            
            $sql = "SELECT status FROM Applicant WHERE person_id = ? AND (status = 'Admitted' OR status = 'Pendient')";
            $result = $conn->execute_query($sql, [$identityNumber]);
            $status = $result->fetch_assoc()['status'] ?? null;

            if (is_null($status) || $status === 'Not Admitted') {
                $sql = "INSERT INTO `Applicant` (person_id, preferend_career_id, secondary_career_id, certify, status) VALUES (?, ?, ?, ?, ?)";
                $conn->execute_query($sql, [$identityNumber, $preferredCareer, $secondaryCareer, $originalFileName, 'Pendient']);
            }else {
                echo 'The applicant cannot register because his or her status is '.$status.'.  <a href="/">Go Back</a>';
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "The applicant with ID $identityNumber is already on the list. <a href="/">Go Back</a>";
            } else {
                // Otros errores
                throw $e;
            }
        }
        
        echo 'Data entered successfully. <a href="/">Go Back</a>';
    } else {
        echo "Error uploading the file.";
    }
} else {
    echo "No file was uploaded.";
}

?>
