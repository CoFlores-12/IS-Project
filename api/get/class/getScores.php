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
}