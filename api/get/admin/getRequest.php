<?php
include '../../../src/modules/database.php';
$db = (new Database())->getConnection();

$Carrers = $db->execute_query("SELECT 
    r.request_id,
    r.request_type_id,
    r.evidence,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    r.comments,
    r.classes_cancel,
    CONCAT(p.indicator, ' ', p.year) as period,
    CONCAT(pe.first_name, ' ', pe.last_name) as full_name
FROM  `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p on r.period_id = p.period_id
INNER JOIN Students s ON r.student_id = s.account_number
INNER JOIN Persons pe ON s.person_id = pe.person_id
where r.request_id = ?", [$_GET['id']]);

$row = $Carrers->fetch_assoc();
if ($row['request_type_id']===2) {
    $request_id =$row['request_id'];
    $pdfContent = $row['evidence'];
    $base64Pdf = base64_encode($pdfContent);
    $date = $row['local_time'];
    $title = $row['title'];
    $student_id = $row['student_id'];
    $full_name = $row['full_name'];
    $comments = $row['comments'];
    $classes_cancel = $row['classes_cancel'];
    $section_ids = json_decode(str_replace(['{', '}'], ['[', ']'], $classes_cancel), true);
    $tableRows = '';

    if ($section_ids && is_array($section_ids)) {
      $id_list = implode(',', $section_ids);
      
      $query = "SELECT 
        section_id,
        c.class_code,
        c.class_name,
        CONCAT(p.first_name, ' ', p.last_name) as teacher,
        (
            SELECT COUNT(*) FROM `Enroll` en
            WHERE en.section_id = s.section_id
        ) as enrolled
       FROM Section s
       inner join Classes c
       ON s.class_id = c.class_id
       INNER JOIN Employees e
       ON s.employee_number = e.employee_number
       INNER JOIN Persons p
       on e.person_id = p.person_id
      WHERE section_id IN ($id_list)
       ";
      
      $result = $db->query($query);
  
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $tableRows .= "<tr>
                  <td>{$row['section_id']}</td>
                  <td>{$row['class_code']} {$row['class_name']}</td>
                  <td>{$row['teacher']}</td>
                  <td>{$row['enrolled']}</td>
              </tr>";
          }
      }
    }
    echo <<<HTML
                <div class="row">
                <div class="col-12 col-md-6">
                  <label for="evidence"><center>Evidencia</center></label>
                  <div id="evidenceContainer">
                    <iframe class="my-2" src="data:application/pdf;base64,$base64Pdf" width="100%" height="500px" style="border: none;"></iframe>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="row">
                    <div class="col-4">
                      <label for="dateRequest">Fecha</label>  
                      <input type="text" class="form-control" id="dateRequest" value="$date" disabled>
                    </div>
                    <div class="col-8">
                      <label for="titleRequest">Tipo de solicitud</label>  
                      <input type="text" class="form-control" id="titleRequest" value="$title" disabled>
                    </div>
                  </div>
                  <div class="row my-4">
                    <div class="col-4">
                      <label for="accountNumberRequest">Numero de cuenta</label>  
                      <input type="text" class="form-control" id="accountNumberRequest" value="$student_id" disabled>
                    </div>
                    <div class="col-8">
                      <label for="fullnameRequest">Nombre completo</label>  
                      <input type="text" class="form-control" id="fullnameRequest" value="$full_name" disabled>
                    </div>
                  </div>
                  <div class="row my-4">
                    <div class="col">
                      <label for="accountNumberRequest">Justificación</label>  
                      <textarea type="text" class="form-control bg-aux text" id="commentsRequest" disabled>$comments</textarea>
                    </div>
                  </div>
                  <div class="row my-4">
                    <div class="col">
                      <label for="classTable">Clases asociadas</label>
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th class="bg text">Sección</th>
                                  <th class="bg text">Clase</th>
                                  <th class="bg text">Docente</th>
                                  <th class="bg text">Matriculados</th>
                              </tr>
                          </thead>
                          <tbody>
                              $tableRows
                          </tbody>
                      </table>
                    </div>
                  </div>
                    <div class="row my-4">
                        <div class="col">
                            <label for="retroRequest">Ingrese un comentario de retroalimentación</label>  
                            <textarea name="" class="form-control bg-aux" id="retroRequest"></textarea>
                            <small class="text-danger d-none" id="retroInvalid">Debe ingresar una justificación</small>
                        </div>
                    </div>
                    <div class="row d-flex justify-between">
                        <button type="button" id="btnValidateReq" class="btn col-5 mr-2 btn-danger" onclick="validateRequest(0,$request_id)">
                        Rechazar
                        </button>
                        <button type="button" id="btnValidateReq1" class="btn col-5 ml-2 btn-success" onclick="validateRequest(1,$request_id)">
                        Aprobar
                        </button>
                    
                    </div>
                </div>
             </div>
             HTML;
}