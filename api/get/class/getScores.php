<?php

session_start();
$idStudent = $_SESSION['user']['student_id'] ?? null;
$idEmployee = $_SESSION['user']['employeenumber'] ?? null;

if ($idStudent === null && $idEmployee === null) {
    echo json_encode([
        "status" => false,
        "error" => "Sin sesión."
    ]);
    exit;
}

include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

//TODO verificar periodo de ingreso de notas
if (false) {
    http_response_code(400);
    echo 'Periodo de subir notas no activo';
    exit;
}

$section_id = $_GET['section_id'] ?? null;

if (!$section_id) {
    echo json_encode([
        "status" => false,
        "error" => "section_id es requerido."
    ]);
    exit;
}

$query = "SELECT data FROM Config LIMIT 1";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $configData = json_decode($row['data'], true);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Config not found']);
        exit;
    }
    $cancellationData = json_decode($configData['uploadNotes'], true);
    $cancellationStart = new DateTime($cancellationData['startTime']);
    $cancellationEnd = new DateTime($cancellationData['endTime']);
    $currentDate = new DateTime();
if ($currentDate >= $cancellationStart && $currentDate <= $cancellationEnd) {
if ($idEmployee != null) {
    $rows = '';
    $result = $db->execute_query("SELECT 
        CONCAT(p.first_name, ' ', p.last_name, ' (', s.account_number, ')') as student,
        s.account_number,
        e.score,
        e.obs_id
        FROM `Enroll` e
        INNER JOIN `Students` s 
        ON e.student_id = s.account_number
        INNER JOIN `Persons` p 
        ON s.person_id = p.person_id
        WHERE e.section_id = ?", [$section_id]);
    $idRow = 1;
    while ($row = $result->fetch_assoc()) {
        $options = [
            "" => ' ',
            0 => 'RPB',
            1 => 'APB',
            2 => 'ABN',
            3 => 'NSP',
        ];
        $rows .= ' <tr>
            <th data-id="'.$row['account_number'].'">'.$row['student'].'</th>
            <th><input type="number" value="'.($row['score']).'" onkeyup="scoreEntered(this)" data-row-id="'.$idRow.'" class="form-control" min="0" max="100"></th>
            <th><select name="" class="form-control" id="select'.$idRow.'">';
        foreach ($options as $value => $label) {
            $obs = $row['obs_id'] != null ? $row['obs_id'] : "";
            $selected = ($obs == $value) ? 'selected' : '';
            $rows .= "<option value=\"$value\" $selected>$label</option>";
        }

        $rows .= '</select>
                </th>
            </tr>';
        $idRow++;
    }

        echo <<<HTML
                <table>
                    <thead>
                        <tr>
                            <th class="bg-aux">Estudiante</th>
                            <th class="bg-aux">Calificación</th>
                            <th class="bg-aux">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                       $rows
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" onclick="saveScores(0)" data-option="0" class="btn btnOptionScores bg-custom-primary text-white">Guardar</button>
                    <button type="button" onclick="saveScores(1)" data-option="1" class="btn btnOptionScores btn-danger text-white">Finalizar</button>
                </div>
            HTML;
 


    exit;
} elseif ($idStudent != null) {
    //TODO validar la evaluación docente esta realizada

    $rows = '';

    $sql = "SELECT COUNT(*) AS count
        FROM student_teacher_evaluation
        WHERE student_account_number = ? 
          AND section_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $idStudent, $section_id);

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row['count'] > 0){
        $result = $db->execute_query("SELECT 
        e.score,
        o.obs_name
        FROM `Enroll` e
        INNER JOIN `Students` s 
        ON e.student_id = s.account_number
        INNER JOIN `Persons` p 
        ON s.person_id = p.person_id
        LEFT JOIN Obs o
        ON e.obs_id = o.obs_id
        WHERE e.section_id = ? AND e.student_id = ?", [$section_id, $idStudent]);
    $row = $result->fetch_assoc();
    try {
        @$rows .= ' <tr>
            <th>Nota Final</th>
            <th>'.$row['score'].'</th>
            <th>'.$row['obs_name'].'</th>
            </tr>';
    } catch (\Throwable $th) {
        //throw $th;
    }
    echo <<<HTML
                <table>
                    <thead>
                        <tr>
                            <th class="bg-aux">Item</th>
                            <th class="bg-aux">Calificación</th>
                            <th class="bg-aux">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                       $rows
                    </tbody>
                </table>
            HTML;
    exit;
    }else{
        echo <<<HTML
    <div class="mt-4">
        <h4>Evaluar A su Docente</h4>
        <div>
            <label for="pregunta1">1. Al iniciar la clase ¿le facilitó por escrito el Programa de la asignatura, que contenía los objetivos de aprendizaje, temas, calendarización de clases y exámenes, formas y criterios de evaluación?</label>
            <select id="pregunta1" name="pregunta1" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta2">2. ¿Demuestra estar actualizado y tener dominio de la disciplina que imparte?</label>
            <select id="pregunta2" name="pregunta2" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta3">3. ¿Establece en la clase relación entre los contenidos teóricos y los prácticos?</label>
            <select id="pregunta3" name="pregunta3" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta4">4. ¿Utiliza en el desarrollo del curso técnicas educativas que facilitan su aprendizaje (investigaciones en grupo, estudio de casos, visitas al campo, seminarios, mesas redondas, simulaciones, audiciones, ejercicio adicionales, sitios web, etc)?</label>
            <select id="pregunta4" name="pregunta4" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta5">5. ¿Utiliza durante la clase medios audiovisuales que facilitan su aprendizaje?</label>
            <select id="pregunta5" name="pregunta5" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta6">6. ¿Relaciona el contenido de esta asignatura con otras asignaturas que usted ya cursó?</label>
            <select id="pregunta6" name="pregunta6" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta7">7. ¿Desarrolló contenidos adecuados en profundidad para el nivel que usted lleva en la carrera?</label>
            <select id="pregunta7" name="pregunta7" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta8">8. ¿Selecciona temas y experiencias que le sean a Usted útiles en su vida profesional y cotidiana?</label>
            <select id="pregunta8" name="pregunta8" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta9">9. Además de las explicaciones, le recomendó en esta clase otras fuentes de consulta para el desarrollo de esta asignatura, accesibles a Usted, en cuanto a costo, ubicación, etc.?</label>
            <select id="pregunta9" name="pregunta9" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta10">10. ¿Incentiva la participación de los estudiantes en la clase?</label>
            <select id="pregunta10" name="pregunta10" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta11">11. ¿Asiste a las clases con puntualidad y según lo programado?</label>
            <select id="pregunta11" name="pregunta11" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta12">12. ¿Inicia y finaliza las clases en el tiempo reglamentario?</label>
            <select id="pregunta12" name="pregunta12" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta13">13. ¿Muestra interés en que usted aprenda?</label>
            <select id="pregunta13" name="pregunta13" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta14">14. ¿Relaciona el contenido de la clase con la vida real?</label>
            <select id="pregunta14" name="pregunta14" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta15">15. ¿Logra mantener la atención de los estudiantes durante el desarrollo de la clase?</label>
            <select id="pregunta15" name="pregunta15" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta16">16. ¿Muestra buena disposición para aclarar y ampliar dudas sobre problemas que surgen durante las clases?</label>
            <select id="pregunta16" name="pregunta16" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta17">17. ¿Trata respetuosamente, a los estudiantes, durante todos los momentos de la clase?</label>
            <select id="pregunta17" name="pregunta17" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta18">18. ¿Mantiene un clima de cordialidad y respeto con todo el grupo de alumnos?</label>
            <select id="pregunta18" name="pregunta18" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta19">19. ¿Brinda orientaciones o lineamientos claros sobre cómo hacer y presentar los trabajos asignados durante la clase?</label>
            <select id="pregunta19" name="pregunta19" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta20">20. ¿Al inicio del periodo le explicó el sistema de evaluación a utilizarse durante el desarrollo del curso?</label>
            <select id="pregunta20" name="pregunta20" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta21">21. ¿Practicó evaluaciones de acuerdo a los objetivos propuestos en las clases, los contenidos desarrollados y en las fechas previstas?</label>
            <select id="pregunta21" name="pregunta21" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta22">22. ¿le entregó los resultados de las pruebas o exámenes y trabajos en el termino de 2 semanas. ?</label>
            <select id="pregunta22" name="pregunta22" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta23">23. ¿En la revisión de las evaluaciones le permitió conocer sus aciertos y discutir sus equivocaciones?</label>
            <select id="pregunta23" name="pregunta23" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta24">24. ¿Da a conocer criterios para calificar y los aplica al revisar los exámenes, prueba, trabajos?</label>
            <select id="pregunta24" name="pregunta24" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta25">25. ¿Utiliza los exámenes y la revisión de estos, como medio para afianzar su aprendizaje?</label>
            <select id="pregunta25" name="pregunta25" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta26">26. ¿Cuál fue su nivel de aprendizaje que tuvo, en esta asignatura?</label>
            <select id="pregunta26" name="pregunta26" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta27">27. ¿Que grado de dificultad le asigna a los contenidos de esta asignatura?</label>
            <select id="pregunta27" name="pregunta27" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="pregunta28">28. ¿En relación al número de alumnos que valor de la escala, asigna al ambiente académico (tamaño del aula, condiciones del mobiliario, condiciones acústicas)?</label>
            <select id="pregunta28" name="pregunta28" class="form-select mt-2" required>
                <option value="">Seleccionar una opción</option>
                <option value=0>Deficiente</option>
                <option value=1>Malo</option>
                <option value=2>Bueno</option>
                <option value=3>Muy Bueno</option>
                <option value=4>Excelente</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="justificacion1">29. Qué cualidad docente identifica Usted en este profesor(a):?</label>
            <textarea id="justificacion1" name="justificacion1" class="form-control bg-aux" rows="4" placeholder="Escriba sus comentarios aquí..."></textarea>
        </div>
        <div class="mt-3">
            <label for="justificacion2">30. A su criterio, ¿en que aspectos de su desempeño docente, su profesor puede mejorar?</label>
            <textarea id="justificacion2" name="justificacion2" class="form-control bg-aux" rows="4" placeholder="Escriba sus comentarios aquí..."></textarea>
        </div>
        <div class="mt-3">
            <label for="justificacion3">31. Ha identificado Usted en su profesor(a) una actitud no acorde con un docente universitario</label>
            <textarea id="justificacion3" name="justificacion3" class="form-control bg-aux" rows="4" placeholder="Escriba sus comentarios aquí..."></textarea>
        </div>
        <div class="alert alert-danger mt-2" hidden id="alertErrorSendSurvey" role="alert">
            Error, todos los campos son requeridos
        </div>
        <div class="mt-4">
            <button type="button" class="btn btn-success" onclick="save()" id="sendSurvey">Enviar</button>
        </div>
    </div>
HTML;

    }

}
} else {
    echo <<<HTML
        <div class="alert alert-danger" role="alert">
            Proceso inactivo
        </div>
    HTML;
}