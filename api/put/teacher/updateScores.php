<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href = '/views/admin/login/index.php?error=401';</script>";
    exit;
}
$userRole = $_SESSION['user']['role'];
$roles = ["Department Head", "Coordinator", "Teacher"];
if (!in_array($userRole, $roles)) {
    echo "<script>window.location.href = '/views/admin/login/index.php?error=402';</script>";
    exit;
}

require_once '../../../src/modules/mails.php'; 
include_once '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$data = $_POST;
$section_id = $data['section_id']; 
$option = $data['option'];

unset($data['section_id'], $data['option']);

$response = ['status' => true, 'message' => 'Operación completada con éxito.'];
$mailer = new Mails(getenv('emailUser'), getenv('emailPassword'));

if ($option == 0) {
    $updateQuery = "UPDATE Enroll SET score = ?, obs_id = ? WHERE section_id = ? AND student_id = ?";
    $checkQuery = "SELECT e.score, e.obs_id, p.personal_email, class_name
FROM Enroll e
INNER JOIN `Students` s ON e.student_id = s.account_number
INNER JOIN `Persons` p ON s.person_id = p.person_id
INNER JOIN `Section` se ON e.section_id = se.section_id
INNER JOIN `Classes` c ON se.class_id = c.class_id WHERE e.section_id = ? AND e.student_id = ?";

    if ($stmt = $conn->prepare($updateQuery)) {
        foreach ($data as $student_id => $info) {
            $decoded = json_decode($info, true);
            $score = $decoded['score'] !== "" ? $decoded['score'] : null;
            $obs_id = $decoded['obs_id'] !== "" ? $decoded['obs_id'] : null;

            if ($stmtCheck = $conn->prepare($checkQuery)) {
                $stmtCheck->bind_param("is", $section_id, $student_id);
                $stmtCheck->execute();
                $stmtCheck->bind_result($currentScore, $currentObsId, $personalEmail, $className);
                $stmtCheck->fetch();
                $stmtCheck->close();
            } else {
                $response = ['status' => false, 'message' => 'Error en la consulta de validación: ' . $conn->error];
                echo json_encode($response);
                exit;
            }

            if ($currentScore != $score || $currentObsId != $obs_id) {
                $stmt->bind_param("iiis", $score, $obs_id, $section_id, $student_id);
                if (!$stmt->execute()) {
                    $response = ['status' => false, 'message' => "Error al actualizar estudiante $student_id: " . $stmt->error];
                    echo json_encode($response);
                    exit;
                }
                if (!empty($score)) {
                    $emailBody = "
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
                                .email-container { max-width: 600px; margin: 20px auto; background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
                                .header { background-color: #176b87; color: #fff; padding: 20px; text-align: center; }
                                .content { padding: 20px; }
                                .footer { background-color: #f4f4f4; text-align: center; padding: 10px; font-size: 12px; color: #555; }
                            </style>
                        </head>
                        <body>
                            <div class=\"email-container\">
                                <div class=\"header\">
                                    <h1>Nota Registrada</h1>
                                </div>
                                <div class=\"content\">
                                    <p>Estimado(a) estudiante con numero de cuenta {$student_id},</p>
                                    <p>Se ha registrado una nota de {$score} en la clase {$className}.</p>
                                    <p>Gracias por tu esfuerzo.</p>
                                    <p>Atentamente,</p>
                                    <p><strong>Equipo Docente</strong></p>
                                </div>
                                <div class=\"footer\">
                                    <p>Este correo es generado automáticamente. Por favor, no responda.</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    ";
                    $mailer->sendEmail(getenv('emailUser'), $personalEmail, 'Nota Registrada', $emailBody); // Reemplaza con el correo del estudiante
                }
            }
        
        }
    } else {
        $response = ['status' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error];
    }
}elseif ($option == 1) {
    foreach ($data as $student_id => $info) {
        $decoded = json_decode($info, true);
        if (empty($decoded['score']) || empty($decoded['obs_id'])) {
            echo json_encode([
                'status' => false,
                'message' => "No se puede mover a History. El estudiante $student_id tiene campos vacíos o nulos."
            ]);
            exit;
        }
    }

    foreach ($data as $student_id => $info) {
        $decoded = json_decode($info, true);
        $score = $decoded['score'] !== "" ? $decoded['score'] : null;
        $obs_id = $decoded['obs_id'] !== "" ? $decoded['obs_id'] : null;

        $insertQuery = "INSERT INTO History (section_id, student_id, score, obs_id) VALUES (?, ?, ?, ?)";
        if ($stmtInsert = $conn->prepare($insertQuery)) {
            $stmtInsert->bind_param("isii", $section_id, $student_id, $score, $obs_id);
            if (!$stmtInsert->execute()) {
                $response = ['status' => false, 'message' => "Error al mover estudiante $student_id a History: " . $stmtInsert->error];
                echo json_encode($response);
                exit;
            }
            $stmtInsert->close();
        } else {
            echo "Error en la preparación de la consulta de inserción: " . $conn->error;
        }

        $deleteQuery = "DELETE FROM Enroll WHERE section_id = ? AND student_id = ?";
        if ($stmtDelete = $conn->prepare($deleteQuery)) {
            $stmtDelete->bind_param("is", $section_id, $student_id);
            if (!$stmtDelete->execute()) {
                $response = ['status' => false, 'message' => "Error al eliminar estudiante $student_id de Enroll: " . $stmtDelete->error];
                echo json_encode($response);
                exit;
            }
            $stmtDelete->close();
        } 
    }
}

$conn->close();
echo json_encode($response);