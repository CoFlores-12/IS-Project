<?php
    if (isset($_POST['submit'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    
        // DB Connection Configs
        $host = 'junction.proxy.rlwy.net';
        $db = 'railway';
        $user = 'root';
        $pass = 'efcUQKAeIGMGtWQfRCLPMenByJTqkuhp';
        $port = '53379';
    
        // Making the Connection
        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    
        // Verifying correct file upload 
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $filename = $_FILES['file']['tmp_name'];
    
            // Opening CSV
            if (($handle = fopen($filename, "r")) !== FALSE) {
                // reading each line
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Assign each value to an specific variable
                    $identity_number = $data[0];
                    $exam_code = $data[1];
                    $result_exam = floatval($data[2]); // explicit convertion to a FLOAT data type
                    //$obs = isset($data[3]) && ($data[3] == 0 || $data[3] == 1) ? (int)$data[3] : 0; // Assuring obs is a bit type number
    
                    // Verifying if identity_number exists on Persons table
                    $check_person_sql = "SELECT COUNT(*) FROM Persons WHERE person_id = ?";
                    $check_person_stmt = $pdo->prepare($check_person_sql);
                    $check_person_stmt->execute([$identity_number]);
                    $person_exists = $check_person_stmt->fetchColumn();
    
                    if ($person_exists > 0) {
                        // identity_number exists on Persons, then continue with the insertion
                        // Verifying if exam_code exists on table Exams
                        $check_exam_sql = "SELECT COUNT(*) FROM Exams WHERE exam_code = ?";
                        $check_exam_stmt = $pdo->prepare($check_exam_sql);
                        $check_exam_stmt->execute([$exam_code]);
                        $exam_exists = $check_exam_stmt->fetchColumn();
    
                        if ($exam_exists > 0) {
                            // exam_code exists, continue with insertion
                            $sql = "INSERT INTO Applicant_result (identity_number, exam_code, result_exam) VALUES (?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$identity_number, $exam_code, $result_exam]);
                        } else {
                            echo "El exam_code '$exam_code' no existe en la tabla Exams.<br>";
                        }
                    } else {
                        echo "El identity_number '$identity_number' no existe en la tabla Persons.<br>";
                    }
                }
                fclose($handle);
                echo "Datos importados con éxito.";
            } else {
                echo "Error al abrir el archivo.";
            }
        } else {
            echo "Error al subir el archivo.";
        }
    }
    
    
    
    
/*if (isset($_POST['submit'])) {
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //include '../../../src/modules/database.php';
    //$conn = (new Database())->getConnection();

    // Configuración de la conexión a la base de datos
    $host = 'junction.proxy.rlwy.net';
    $db = 'railway';
    $user = 'root';
    $pass = 'efcUQKAeIGMGtWQfRCLPMenByJTqkuhp';
    $port = '53379';

    // Conectar a la base de datos usando PDO
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    // Verifica que el archivo fue subido correctamente
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['tmp_name'];
        
        // Abre el archivo CSV
        if (($handle = fopen($filename, "r")) !== FALSE) {
            // Leer cada línea del archivo
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Suponiendo que la tabla tiene columnas 'col1', 'col2', 'col3'
                $sql = "INSERT INTO Applicant_result (identity_number, exam_code, result_exam, obs) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
            }
            fclose($handle);
            echo "Datos importados con éxito.";
        } else {
            echo "Error al abrir el archivo.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}*/
?>