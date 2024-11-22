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

<!-- Modal Admitted -->
<div class="modal fade" id="admittedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Admitteds</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <a class="btn text-success btn-outline-success" href="/api/get/admin/admittedStudents.php">
        Export Admitteds <i class="bi bi-arrow-bar-up"></i>
    </a>
    </div>
    </div>
  </div>
</div>
<!-- Modal Admitted -->

<!-- Modal Applicant -->
<div class="modal fade" id="applicantModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
<div class="modal fade" id="addExamModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
    <div class="modal fade" id="csvUploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <button type="submit" name="submit" class="btn btn-primary mt-2">Upload and Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Result Message -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="successModalLabel">Upload Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                    <button id="nextActionBtn" class="btn btn-primary">Validate Applicant Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para éxito de validación -->
    <div class="modal fade" id="finalSuccessModal" tabindex="-1" aria-labelledby="finalSuccessModalLabel" aria-hidden="true">
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

<div class="main">
        <div class="offcanvas offcanvas-start bg" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                
                <button id="addExamnBtn" class="w-full bg-aux text btn rounded">Add Exam</button>
                <button id="csvUploadBtn" class="w-full bg-aux text btn rounded mt-3" data-bs-toggle="modal" data-bs-target="#csvUploadModal">Upload Exam Results</button>
            </div>
        </div>
        <div class="header p-2 text-inverter bg">
            <div class="flex justify-between">
                <button class="btn bg text" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="btn-group">
                    <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                        
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">My profile</a></li>
                        <li><a class="dropdown-item" href="#">Messages</a></li>
                        <li><a class="dropdown-item" href="#">requests</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Dashboard</h4>
               
            </div>
            <div class="row p-4">
                <div class="col">
                    <div id="cardApplicant" class="card bg-aux shadow rounded m-2  p-2" data-bs-toggle="modal" data-bs-target="#applicantModal">
                        <span>Applicants</span>
                        <strong><?php echo $applicant; ?></strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Students</span>
                        <strong><?php echo $students; ?></strong>
                    </div>
                </div>
                <div class="col">
                    <div id="cardAdmitted" class="card shadow rounded m-2 bg-aux p-2" data-bs-toggle="modal" data-bs-target="#admittedModal">
                        <span>Admitted</span>
                        <strong><?php echo $admitted; ?></strong>
                    </div>
                </div>
                
            </div>
            <div class="row p-4">
                <h4 class="text">Proceso de admisión</h4>
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2  p-2" >
                        <span>Subir CSV</span>
                        <small class="my-2">Click aqui para descargar la plantilla</small>
                        <button type="button" class="btn btn-primary">Subir</button>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Validar resultados</span>
                        <button type="button" class="btn btn-primary" disabled>Empezar</button>
                    </div>
                </div>
                <div class="col">
                    <div  class="card shadow rounded m-2 bg-aux p-2" >
                        <span>Enviar Correos</span>
                        <button type="button" class="btn btn-primary" disabled>Empezar</button>
                    </div>
                </div>
                
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
