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
    CONCAT(pe.first_name, ' ', pe.last_name) as full_name,
    crs.career_name,
    rc.center_name
FROM  `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p on r.period_id = p.period_id
INNER JOIN Students s ON r.student_id = s.account_number
INNER JOIN Persons pe ON s.person_id = pe.person_id
LEFT JOIN Careers crs ON s.career_id = crs.career_id
LEFT JOIN Regional_center rc ON pe.center_id = rc.center_id
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
} elseif ($row['request_type_id']===3) {
  $date = $row['local_time'];
  $title = $row['title'];
  $student_id = $row['student_id'];
  $full_name = $row['full_name'];
  $comments = $row['comments'];
  $career_name = $row['career_name'];
  $Carrers = $db->execute_query("SELECT ap.* FROM 
    Students s
    inner join Persons p ON s.person_id = p.person_id
    inner join Applicant_result ap on p.person_id = ap.identity_number
    WHERE s.account_number = ?
  ", [$student_id]);
  $examenResult = '';
  while ($rowEx = $Carrers->fetch_assoc()) {
    $examenResult .= '<tr>
                          <th>'.$rowEx['exam_code'].'</th>
                          <th>'.$rowEx['result_exam'].'</th>
                        </tr>';
  }
  $index = $db->execute_query("SELECT 
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND p1.active = 0 
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
    FROM 
        History h
    JOIN 
        `Section` s ON h.section_id = s.section_id
    JOIN 
        `Classes` c ON s.class_id = c.class_id
    WHERE 
        h.student_id = ?
    GROUP BY 
        h.student_id;
    ", [$student_id]);
  $indexRow = $index->fetch_assoc();

  @$indiceGlobal = intval($indexRow['indice_global']);
  @$indiceUltimoPeriodo = intval($indexRow['indice_ultimo_periodo']); 

  echo <<<HTML
              <div class="row">
              
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
                  <div class="col-12">
                    <label for="accountNumberRequest">Carrera Actual</label>  
                    <input type="text" class="form-control" id="accountNumberRequest" value="$career_name" disabled>
                  </div>
                </div>
                <div class="row my-4">
                  <div class="col">
                    <label for="accountNumberRequest">Justificación</label>  
                    <textarea type="text" class="form-control bg-aux" id="commentsRequest" disabled>$comments</textarea>
                  </div>
                </div>
                  
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <label for="dateRequest">Resultados examen</label>  
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="bg-custom-primary text-white">Examen</th>
                          <th class="bg-custom-primary text-white">Puntaje</th>
                        </tr>
                      </thead>
                      <tbody>
                        $examenResult
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row my-4">
                  <div class="col-6">
                    <label for="accountNumberRequest">Indice global</label>  
                    <input type="text" class="form-control" id="s" value="$indiceGlobal" disabled>
                  </div>
                  <div class="col-6">
                    <label for="fullnameRequest">Indice ultimo periodo</label>  
                    <input type="text" class="form-control" id="b" value="$indiceUltimoPeriodo" disabled>
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
}  elseif ($row['request_type_id']===4) {
  $date = $row['local_time'];
  $title = $row['title'];
  $student_id = $row['student_id'];
  $full_name = $row['full_name'];
  $comments = $row['comments'];
  $career_name = $row['career_name'];
  $center_name = $row['center_name'];
  
  $index = $db->execute_query("SELECT 
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND p1.active = 0 
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
    FROM 
        History h
    JOIN 
        `Section` s ON h.section_id = s.section_id
    JOIN 
        `Classes` c ON s.class_id = c.class_id
    WHERE 
        h.student_id = ?
    GROUP BY 
        h.student_id;
    ", [$student_id]);
$indexRow = $index->fetch_assoc();

@$indiceGlobal = intval($indexRow['indice_global']);
@$indiceUltimoPeriodo = intval($indexRow['indice_ultimo_periodo']); 

  echo <<<HTML
              <div class="row">
              
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
                  <div class="col-12">
                    <label for="accountNumberRequest">Carrera</label>  
                    <input type="text" class="form-control" id="accountNumberRequest" value="$career_name" disabled>
                  </div>
                </div>
                <div class="row my-4">
                  <div class="col-12">
                    <label for="accountNumberRequest">Centro Actual</label>  
                    <input type="text" class="form-control" id="accountNumberRequest" value="$center_name" disabled>
                  </div>
                </div>
               
                  
              </div>
              <div class="col-12 col-md-6">
              <div class="row my-4">
                  <div class="col">
                    <label for="accountNumberRequest">Justificación</label>  
                    <textarea type="text" class="form-control bg-aux" id="commentsRequest" disabled>$comments</textarea>
                  </div>
                </div>
                <div class="row my-4">
                  <div class="col-6">
                    <label for="accountNumberRequest">Indice global</label>  
                    <input type="text" class="form-control" id="s" value="$indiceGlobal" disabled>
                  </div>
                  <div class="col-6">
                    <label for="fullnameRequest">Indice ultimo periodo</label>  
                    <input type="text" class="form-control" id="b" value="$indiceUltimoPeriodo" disabled>
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