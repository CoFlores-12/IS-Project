<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Conexión a la base de datos
    include './../../../src/modules/database.php';
    $db = (new Database())->getConnection();

    // Leer el class_code enviado desde el frontend
    $data = json_decode(file_get_contents('php://input'), true);
    $class_code = $data['class_code'] ?? null;

    if (!$class_code) {
        echo json_encode(['error' => 'Class code is required']);
        exit;
    }

    try {
        // Paso 1: Buscar en la tabla Classes por class_code
        $query = "SELECT class_id FROM Classes WHERE class_code = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $class_code);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($class_id);
        if (!$stmt->fetch()) {
            echo json_encode(['error' => 'Class code not found']);
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Paso 2: Buscar en la tabla Section por class_id
        $query = "SELECT section_id, hour_start, hour_end FROM Section WHERE class_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $class_id);
        $stmt->execute();
        $stmt->store_result();

        $sections = [];
        $stmt->bind_result($section_id, $hour_start, $hour_end);
        while ($stmt->fetch()) {
            $sections[] = [
                'section_id' => $section_id,
                'hour_start' => $hour_start,
                'hour_end' => $hour_end,
            ];
        }
        $stmt->close();

        if (empty($sections)) {
            echo json_encode(['error' => 'No sections found for this class']);
            exit;
        }

        // Paso 3: Buscar en la tabla Waitlist y StudentxWaitlist por section_id
        $results = [];
        foreach ($sections as $section) {
            $query = "SELECT waitlist_id FROM Waitlist WHERE section_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('i', $section['section_id']);
            $stmt->execute();
            $stmt->store_result();

            $stmt->bind_result($waitlist_id);
            while ($stmt->fetch()) {
                // Obtener el conteo de estudiantes en StudentxWaitlist
                $query2 = "SELECT COUNT(*) as student_count FROM StudentsxWaitlist WHERE waitlist_id = ?";
                $stmt2 = $db->prepare($query2);
                $stmt2->bind_param('i', $waitlist_id);
                $stmt2->execute();
                $stmt2->store_result();

                $stmt2->bind_result($student_count);
                $stmt2->fetch();
                $stmt2->close();

                // Agregar resultados al array final, incluyendo el class_code
                $results[] = [
                    'waitlist_id' => $waitlist_id,
                    'section_id' => $section['section_id'],
                    'hour_start' => $section['hour_start'],
                    'hour_end' => $section['hour_end'],
                    'student_count' => $student_count,
                    'class_code' => $class_code, // Se incluye el class_code aquí
                ];
            }
            $stmt->close();
        }

        echo json_encode(['data' => $results]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
