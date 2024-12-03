<?php
// Incluir el archivo de conexión a la base de datos
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

// Verificar que el parámetro section_id está presente
if (!isset($_GET['section_id'])) {
    echo json_encode(["error" => "section_id no proporcionado"]);
    exit;
}

$section_id = $_GET['section_id'];

// Realizar la consulta para obtener el employee_number desde la tabla Section
$sql = "SELECT employee_number FROM Section WHERE section_id = ?";
$stmt = $db->prepare($sql);  // Usar $db en lugar de $connection
$stmt->bind_param("i", $section_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró la sección
if ($result->num_rows > 0) {
    $section = $result->fetch_assoc();
    $employee_number = $section['employee_number'];

    // Realizar la consulta para obtener el person_id desde la tabla Employees usando el employee_number
    $sql = "SELECT person_id FROM Employees WHERE employee_number = ?";
    $stmt = $db->prepare($sql);  // Usar $db en lugar de $connection
    $stmt->bind_param("i", $employee_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $person_id = $employee['person_id'];

        // Realizar la consulta para obtener los nombres del docente desde la tabla Persons usando el person_id
        $sql = "SELECT first_name, last_name FROM Persons WHERE person_id = ?";
        $stmt = $db->prepare($sql);  // Usar $db en lugar de $connection
        $stmt->bind_param("i", $person_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $person = $result->fetch_assoc();
            $teacher_name = $person['first_name'] . ' ' . $person['last_name'];

            // Devolver el nombre completo del docente
            echo json_encode(["teacher_name" => $teacher_name]);
        } else {
            echo json_encode(["error" => "No se encontró al docente"]);
        }
    } else {
        echo json_encode(["error" => "Empleado no encontrado"]);
    }
} else {
    echo json_encode(["error" => "Sección no encontrada"]);
}

$stmt->close();
$db->close();  // Usar $db en lugar de $connection
?>
