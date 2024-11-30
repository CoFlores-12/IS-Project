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
    $pdfContent = $row['evidence'];
    $base64Pdf = base64_encode($pdfContent);
    $date = $row['local_time'];
    $title = $row['title'];
    $student_id = $row['student_id'];
    $full_name = $row['full_name'];
    $comments = $row['comments'];
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
                      <textarea type="text" class="form-control bg-aux" id="commentsRequest" disabled>$comments</textarea>
                    </div>
                  </div>
                    <div class="row my-4">
                        <div class="col">
                            <label for="retroRequest">Ingrese un comentario de retroalimentación</label>  
                            <textarea name="" class="form-control bg-aux" id="retroRequest"></textarea>
                        </div>
                    </div>
                    <div class="row d-flex justify-between">
                        <button type="button" class="btn col-5 mr-2 btn-danger">
                        Rechazar
                        </button>
                        <button type="button" class="btn col-5 ml-2 btn-success">
                        Aprobar
                        </button>
                    
                    </div>
                </div>
             </div>
             HTML;
}