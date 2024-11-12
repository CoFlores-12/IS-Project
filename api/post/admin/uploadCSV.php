<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    die("Connection Error: " . $e->getMessage());
}

ob_start(); // Capturar cualquier salida inesperada

$response = [];

try {
    // Verificar si el archivo se subió correctamente
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['tmp_name'];

        // Conexión a la base de datos
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Procesar CSV
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $identity_number = $data[0];
                $exam_code = $data[1];
                $result_exam = floatval($data[2]);

                $check_person_sql = "SELECT COUNT(*) FROM Persons WHERE person_id = ?";
                $check_person_stmt = $pdo->prepare($check_person_sql);
                $check_person_stmt->execute([$identity_number]);
                $person_exists = $check_person_stmt->fetchColumn();

                if ($person_exists > 0) {
                    $check_exam_sql = "SELECT COUNT(*) FROM Exams WHERE exam_code = ?";
                    $check_exam_stmt = $pdo->prepare($check_exam_sql);
                    $check_exam_stmt->execute([$exam_code]);
                    $exam_exists = $check_exam_stmt->fetchColumn();

                    if ($exam_exists > 0) {
                        $sql = "INSERT INTO Applicant_result (identity_number, exam_code, result_exam) VALUES (?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$identity_number, $exam_code, $result_exam]);
                    }
                }
            }
            fclose($handle);
            $response['success'] = true;
            $response['message'] = 'Data Succesfully Imported.';
        } else {
            $response['success'] = false;
            $response['error'] = 'Error opening the file.';
        }
    } else {
        $error_code = $_FILES['file']['error'];
        $response['success'] = false;
        $response['error'] = "Error uploading file. Error Code: $error_code";
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = 'Processing Error: ' . $e->getMessage();
}

ob_clean(); // Limpiar cualquier salida previa
echo json_encode($response);
?>