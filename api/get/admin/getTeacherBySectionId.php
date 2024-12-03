<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

// Verificar que se haya pasado el parámetro 'section_id'
if (isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];

    // Consulta para obtener el número de empleado usando el section_id en la tabla 'Section'
    $query = "
        SELECT employee_number 
        FROM Section 
        WHERE section_id = ? 
        LIMIT 1
    ";
    
    // Preparar la consulta
    if ($stmt = $db->prepare($query)) {
        // Vincular el parámetro 'section_id' (entero)
        $stmt->bind_param('i', $section_id);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        // Verificar si se encuentra un registro
        if ($result->num_rows > 0) {
            // Obtener los datos de la sección
            $section = $result->fetch_assoc();
            // Retornar el número de empleado asociado a la sección
            echo json_encode([
                'employee_number' => $section['employee_number']
            ]);
        } else {
            // Si no se encuentra ningún registro para ese 'section_id'
            echo json_encode(['error' => 'Sección no encontrada']);
        }
        
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la consulta']);
    }
} else {
    // Si no se pasa el parámetro 'section_id'
    echo json_encode(['error' => 'Se requiere el parámetro section_id']);
}
?>
