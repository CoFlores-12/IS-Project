<?php 
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Student';

AuthMiddleware::checkAccess($requiredRole);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.1.0">
    <meta name="date" content="01/11/2014">
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/homeStudents.css">
</head>
<body>
<div class="toast-container top-0 start-50 translate-middle-x mt-3">
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
<div class="modal fade" id="MyRequestModal" tabindex="-1" aria-labelledby="MyRequestLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg">
        <h5 class="modal-title" id="logModalLabel">Mis solicitudes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg">

        <table class="table table-striped bg">
          <thead>
            <tr>
              <th class="bg text">Fecha</th>
              <th class="bg text">Tipo</th>
              <th class="bg text">Estado</th>
              <th class="bg text">Comentarios</th>
            </tr>
          </thead>
          <tbody id="RequestTableBody">
          </tbody>
        </table>

        
      </div>
      
    </div>
  </div>
</div>
<!-- Modal Requests -->
<div class="modal fade" id="modalRequests" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Crear solicitud</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select name="requestType" class="form-control mb-2" id="requestType">
            <option value="">Seleccionar tipo de solicitud</option>
            <option value="1">Reposición</option>
            <option value="2">Cancelación excepcional</option>
            <option value="3">Cambio de carrera</option>
            <option value="4">Cambio de centro regional</option>
        </select>
        <div id="dataForRequest">
            
        </div>
    </div>
    </div>
  </div>
</div>

<!-- Modal Enrollment -->
<div class="modal fade" id="modalEnrolment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl  modal-dialog-scrollable">
    <div class="modal-content bg">
          <div class="modal-body">
            <div class="row relative">
                <ul class="nav nav-tabs w-full">
                    <li class="nav-item">
                        <button id="addEnrollmentBtn" class="nav-link  bg-aux active  text " aria-current="page">Matricular clase</button>
                    </li>
                    <li class="nav-item">
                        <button id="cancelEnrollmentBtn" class="nav-link text">Cancelar Clase</button>
                    </li>
                </ul>
                <button type="button" class="btn-close absolute right-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
        <div id="form-data">
            <center><div class="spinner-border text m-4" role="status"></div></center>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn bg-custom-primary text-white" disabled id="enrollBtn">Matricular</button>
        <button class="btn bg-custom-primary text-white d-none" disabled id="cancelBtn">Cancelar Clase</button>
    </div>
    </div>
  </div>
</div>
    <div class="container-fluid row h-full">
        <div class="offcanvas offcanvas-end bg p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Mensajería <button class="btn mx-2" id="refreshChats"><i  id="refreshIcon" class="bi bi-arrow-clockwise text rotate"></i></button></h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="body h-full" id="bodyOffCanvas">
                <iframe id="frameChats" src="/views/chats/index.php" class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div>

        <div class="col-md-3 d-none d-md-block bg-aux" id="desktopAside">
              <div class="offcanvas-header py-3 justify-between">
                  <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                  <button type="button" class="btn d-md-none" aria-label="Close" onclick="toggleSidebar()">
                    <i class="bi bi-x-lg"></i>
                  </button>
              </div>
              <div class="list-group">
                  
                            <button id="btnModalRequests" type="button" class="text bg btn mb-1">
                                Crear Solicitud
                            </button>
                            <button id="btnModalMyRequests" type="button" class="text bg btn mb-1">
                                Ver mis Solicitudes
                            </button>
                            <button id="btnModalEnrollment" type="button" class="text bg btn my-1">
                                Matricula
                            </button>
                            
                            
                    
                </div>
        </div>
        <div class="col">
            <div class="header p-2 text-inverter bg">
                <div class="flex justify-between">
                    <button class="btn bg text" type="button" id="toggleAside">
                      <i class="bi bi-list"></i>
                  </button>
                    
                    <div class="btn-group">
                        <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                            
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/views/students/profile/index.php?account_number=<?php echo $_SESSION['user']['student_id'] ?>">Mi perfil</a></li>
                            <li><a class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">Mensajería</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                        </ul>
                     </div>
                </div>
            </div>
            <div class="courses-container">
                <h5 class="text-md font-bold pt-4 pl-4">Clases matriculadas</h5>
                <div class="courses pl-4 pr-4 pb-4" id="courseRunning">
                    <div class="card card-course shadow">
                        <div class="p-0 card-bd flex flex-column">
                            <div class="name w-full p-2 bg-secondary text-white mb-1">
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder col-4"></span>
                                </p>
                            </div>
                            <div class="infoClass p-3">
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder bg-secondary col-12"></span>
                                    <span class="placeholder bg-secondary col-4"></span>
                                    <span class="placeholder bg-secondary col-6"></span>
                                    <span class="placeholder bg-secondary col-8"></span>
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="text-md font-bold pt-4 pl-4">Historial de clases</h5>
                <div class="courses pl-4 pr-4 pb-4" id="courseHistory">
                    <div class="card card-course shadow">
                        <div class="p-0 card-bd flex flex-column">
                            <div class="name w-full p-2 bg-secondary text-white mb-1">
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder col-4"></span>
                                </p>
                            </div>
                            <div class="infoClass p-3">
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder bg-secondary col-12"></span>
                                    <span class="placeholder bg-secondary col-4"></span>
                                    <span class="placeholder bg-secondary col-6"></span>
                                    <span class="placeholder bg-secondary col-8"></span>
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/homeStudent.js"></script>
</body>
</html>