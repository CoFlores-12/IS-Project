<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Admissions';

AuthMiddleware::checkAccess($requiredRole);
include './../../../../src/modules/database.php';

$db = (new Database())->getConnection();

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Applicant WHERE status_id = 0");
$applicant = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Students");
$students = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Applicant WHERE status_id = 1");
$admitted = $result->fetch_assoc()['count'];
$result = $db->execute_query("SELECT COUNT(*) AS count FROM Employees WHERE role_id = 6");
$validators = $result->fetch_assoc()['count'];


$result = $db->execute_query("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.AdmissionsStatus')) as AdmissionsStatus
        FROM Config
        WHERE config_id = 1;");
$AdmissionsStatus= json_decode($result->fetch_assoc()['AdmissionsStatus']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Home</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        #applicantTable,#tableSections, #tableWaitList{
            width: 100%;
        }
        td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}
        .selected {
            background-color: var(--primary-color);
            color: #FFF;
        }
    </style>
</head>
<body>



<!-- Modal Applicant -->
<div class="modal fade" id="applicantModal"  tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Applicants</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="filters row align-items-center">
            <div class="col-3">
                <label for="" >Filter By:</label>
            </div>
            <div class="col">
                <select class="form-control" name="" id="filterCareer">
                    <option value="">Career</option>
                </select>
            </div>
            <div class="col">
                <select class="form-control" name="" id="filterExam">
                    <option value="">Exam</option>
                </select>
            </div>
        </div>
        <table id="applicantTable" class="my-2">
            <thead>
                <tr>
                    <td>Identity</td>
                    <td>Full name</td>
                    <td>1st Career</td>
                    <td>2nd Career</td>
                    <td>Examns</td>
                </tr>
            </thead>
           <tbody id="aspTableBody">
           </tbody>
            
        </table>
    </div>
    <div class="modal-footer justify-between w-full items-center">
        <div class="rpp row item-center">
        <select id="rowsPerPageSelect" class="form-select w-auto">
            <option value="2">2</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
        </select>
        <label for="rowsPerPageSelect" class=" text-xs w-content"><small>Resultados por pagina</small></label>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0" id="pagination">
                <!-- Las páginas se insertarán aquí -->
            </ul>
        </nav>
    </div>
    
    </div>
  </div>
</div>
<!-- Modal Applicant -->

<!-- Modal Add Exam -->
<div class="modal fade" id="addExamModal"  tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Add Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <datalist id="examnsToCareers">
      </datalist>
      <div class="modal-body" id="addExamnModalBody">
        <center><div class="spinner-grow text" role="status"></div></center>
        
    </div>
    </div>
  </div>
</div>
<!-- Modal Add Exam -->

<!-- Modal CSV Upload -->
<div class="modal fade" id="csvUploadModal"  tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="staticBackdropLabel">Upload a CSV with the admissions results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="csvUploadForm" method="post" enctype="multipart/form-data">
                    <label for="file">Select a CSV file:</label>
                    <input type="file" name="file" id="file" accept=".csv" required>
                    <button type="submit" name="submit" class="btn bg-custom-primary text-white mt-2">Upload and Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Result Message -->
<div class="modal fade" id="successModal" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="successModalLabel">Upload Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
                <button id="nextActionBtn" class="btn bg-custom-primary text-white">Validate Applicant Results</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Result Message -->
<div class="modal fade" id="successEmails" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="successModalLabel">Enviar Correos de resultados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="alert alert-success" id="alertSuccesMails" role="alert">
                Proceso activado correctamente
            </div>
            <div class="alert alert-danger"  id="alertFalitedMails" role="alert">
                Algo salio Mal
            </div>
                    <button id="sendMails" class="btn btn-success">Mandar correos</button>
                </div>
        </div>
    </div>
</div>

<!-- Modal para éxito de validación -->
<div class="modal fade" id="finalSuccessModal" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="finalSuccessModalLabel">Task Completed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The validation of admission results has been successful.</p>
                <button id="goToNextTask" class="btn btn-success">E-mail Results</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- Modal New User -->
<div class="modal fade" id="newUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Nuevo Validador</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form class="needs-validation bg rounded p-4" id="newUSerForm" novalidate>
            <div class="form-row d-flex gap-4">
                <div class="col mb-3">
                    <label for="name">Nombres</label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Ejemplo: Juan" required 
                        pattern="[a-zA-Z\s]{4,}"
                    aria-label="Campo para ingresar su nombre completo" aria-required="true" 
                        aria-describedby="nameFeedback">
                    <div id="nameFeedback" class="invalid-feedback">Por favor ingrese su nombre.</div>
                </div>
                <div class="col mb-3">
                    <label for="lastName">Apellidos</label>
                    <input name="lastName" type="text" class="form-control" id="lastName" placeholder="Ejemplo: Pérez" required 
                        aria-label="Campo para ingresar sus apellidos" aria-required="true" 
                        pattern="[a-zA-Z\s]{4,}"
                        aria-describedby="lastNameFeedback">
                    <div id="lastNameFeedback" class="invalid-feedback">Por favor ingrese sus apellidos.</div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <label for="identity">Número de identidad</label>
                    <input name="identity" pattern="\d{4}-\d{4}-\d{5}" id="identity" type="text" class="form-control" placeholder="0801-2000-00000" maxlength="15" required 
                        aria-label="Campo para ingresar su número de identidad" aria-required="true" 
                        aria-describedby="identityFeedback">
                    <div id="identityFeedback" class="invalid-feedback">Por favor ingrese su número de identidad.</div>
                </div>
            </div>
            <div class="form-row d-flex gap-4">
                <div class="col-4 mb-3">
                    <label for="phone">Número de teléfono</label>
                    <input name="phone" id="phone" type="text" class="form-control" placeholder="99999999" maxlength="8" required 
                        pattern="[389]\d{3}\d{4}" 
                            aria-label="Campo para ingresar su número de teléfono" aria-required="true" 
                        aria-describedby="phoneFeedback">
                    <div id="phoneFeedback" class="invalid-feedback">Por favor ingrese un número de teléfono válido.</div>
                </div>
                <div class="col mb-3">
                    <label for="email">Correo electrónico</label>
                    <input name="email" id="email" type="email" class="form-control" placeholder="correo@ejemplo.com" required 
                        aria-label="Campo para ingresar su correo electrónico" aria-required="true" 
                        aria-describedby="emailFeedback">
                    <div id="emailFeedback" class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                </div>
            </div>
           
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" id="createUserBtn" class="btn bg-custom-primary text-white">Crear usuario</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal New User -->

<div class="main">
<div class="toast-container top-50 start-50 translate-middle mt-3">
    <div class="toast border-0" id="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg border border-0">
            <img src="/public/images/logo.png" width="24px" class="rounded me-2" alt="...">
            <strong class="me-auto text" id="toastTitle"></strong>
            <small class="text">Justo ahora</small>
            <button type="button" class="btn-close text" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-aux border border-0" id="toastBody">
        </div>
    </div>
</div>
        <div class="header p-2 text-inverter bg">
            <div class="flex justify-between">
                <button class="btn bg text" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    
                </button>
                
                <div class="btn-group">
                    <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                        
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Admisiones</h4>
               
            </div>
            <div class="row p-4">
                <div class="col">
                    <div id="cardApplicant" class="card bg-aux shadow rounded m-2  p-2" data-bs-toggle="modal" data-bs-target="#applicantModal">
                        <div class="card-body">
                        <span>Aspirantes</span><br>
                        <strong><?php echo $applicant; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <div class="card-body">
                        <span>Estudiantes</span><br>
                        <strong><?php echo $students; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div id="cardAdmitted" class="card shadow rounded m-2 bg-aux p-2" data-bs-toggle="modal" data-bs-target="#admittedModal">
                        <div class="card-body">
                        <div class="row justify-between items-center">
                            <span class="w-content">Admitidos</span>
                            <a class="btn w-content box-sizing-border text-success btn-outline-success" href="/api/get/admin/admittedStudents.php">
                                Exportar <i class="bi bi-arrow-bar-up"></i>
                            </a>
                        </div>
                        <strong><?php echo $admitted; ?></strong>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row p-4">
                <div class="flex flex-row justify-between items-center">
                    <h4 class="text">Validadores</h4>
                    <button id="addUserBtn" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#newUserModal">Agregar validador</button>
                </div>
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2 p-2">
                        <div class="card-body">
                            <p>Validadores: <span id="totalValidators"><?php echo $validators ?></span></p>
                            <center><button
                                type="button"
                                id="applicantsxValidatorBtn"
                                class="btn bg-custom-primary text-white m-2"
                            >
                                Asignar aspirantes a validadores
                            </button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-4">
                <h4 class="text">Proceso de admisión</h4>
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2  p-2" >
                        <span>Subir CSV</span>
                        <small class="my-2">Click <a href="/api/get/public/csvScoresTemplate.php">aqui</a> para descargar la plantilla</small>
                        <button type="button" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#csvUploadModal" <?php echo $AdmissionsStatus == 0 ? "" : "disabled" ?> >Subir</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Validar resultados</span>
                        <button type="button" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#successModal" <?php echo $AdmissionsStatus == 1 ? "" : "disabled" ?> >Empezar</button>
                    </div>
                </div>
                <div class="col">
                    <div  class="card shadow rounded m-2 bg-aux p-2" >
                        
                    <span>Mandar correos</span>
                        <button type="button" class="btn bg-custom-primary text-white my-2"  data-bs-toggle="modal" data-bs-target="#successEmails" <?php echo $AdmissionsStatus == 2 ? "" : "disabled" ?> >Empezar</button>
                    </div>
                </div>
                
            </div>

            <div class="row p-4">
                <button id="addExamnBtn" class="w-full bg-aux text btn rounded">Agregar Examen</button>
            </div>
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/csvUploadForm.js"></script>
    <script src="/public/js/applicantResultValidation.js"></script>
    <script src="/public/js/emailApplicantResult.js"></script>
    <script src="/public/js/admissionsHome.js"></script>
</body>
</html>
