<?php
require '../../../../src/modules/Auth.php';

$requiredRole = 'Department Head';

AuthMiddleware::checkAccess($requiredRole);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departament Head | Home</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/homeTeacher.css">
    <style>
        .list-group-title {
            border: none !important;
            font-weight: bold;
            outline: none;
        }
        .list-group-item-indent {
            padding-left: 2rem; 
            border: none; 
        }
        .list-group-parent {
            padding-left: 2rem; 
            border: none; 
        }
        table {
          width: 100%;
        }
        td {
          background-color: var(--bg-aux) !important;
          color: var(--text) !important;
        }
        thead td {
          border: 1px solid grey;
        }
        tr {
          margin: 1.15rem !important;
          cursor: pointer;
        }
        .successAlert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050; 
            width: 80%;
            max-width: 500px;
        }
        .table-no-border {
            border: none;
        }

        .table-no-border th, .table-no-border td {
            border: none;
            background-color: transparent;
            color: #fff
        }
    </style>
</head>
<body>

<div class="alert alert-success successAlert" hidden id="alertSuccess" role="alert">
  Seccion creada corectamente.
</div>

<div class="modal fade" id="historyStudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg  modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">View History</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p">
            <div class="col-9 flex justify-center items-center">
                <input type="text" id="inputHistory" class="w-full" placeholder="Enter Account Number, identity or email of Student">
            </div>
            <div class="col-3">
                <button id="btnSearchHistory" class="btn bg-custom-primary text-white">Search</button>
            </div>
        </div>
        <div id="historyBody">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-success successAlert" hidden id="alertSuccessEmail"  role="alert">
  Correo enviado Correctamente.
</div>

<div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cambio de Contraseña</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p">
            <div class="col-9 flex justify-center items-center">
                <input type="text" id="inputTeacher" class="w-full" placeholder="Ingrese el numero de emplado, identificaion o correo institucional del docente">
            </div>
            <div class="col-3">
                <button id="btnSearcTeacher" class="btn bg-custom-primary text-white">Buscar</button>
            </div>
        </div>
        <div id="resetBody" class="pt-3">
          
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="newSection" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar nueva sección</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="file" name="" accept=".csv" id="csvFile">
        <div class="alert alert-warning mt-3" id="alertUploadFile" role="alert" >
          Cargar archivo
        </div>
        <button type="button" class="btn bg-custom-primary mt-4 form-control text" id="uploadFile">Cargar Archivo</button>
        <hr class="my-2">
        <div class="table-responsive">
          <table class="table w-100 table-dark table-striped" id="table">
            <thead>
              <tr>
                <th scope="col">Line</th>
                <th scope="col">Observation</th>

              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
      </div>
        <button type="button" id="newSectionManualBtn" class="btn btn-primary mt-4 form-control">Manual</button>
      </div>
    </div>
  </div>
</div>
        
<div class="modal fade" id="newSectionManual" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar nueva seccion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p">
      
        <label class="mb-1" require>Clases:</label>
        <select class="form-select mb-3" id="classes" required>
            <option value="" selected >Seleccione...</option>
        </select>

        <label class="mb-1">Docente:</label>
        <select class="form-select mb-3" id="teachers">
            <option value="" selected>Seleccione...</option>
        </select>
        <div class="alert alert-danger" role="alert" id="alertTeacher">
          El docente esta ocupado a esta hora, elija otro.
        </div>
        <label class="mb-1">Aulas:</label>
        <select class="form-select mb-3" id="classrooms">
            <option value="" selected>Seleccione...</option>
        </select>
        <div class="alert alert-danger" role="alert" id="alertClassroom">
          El aula esta ocupada a esta hora, elija otra.
        </div>        
        <label class="mb-1">Marque los dias:</label>
        <div class="days">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Mon">
            <label class="form-check-label" for="inlineCheckbox1">Lun</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Tue">
            <label class="form-check-label" for="inlineCheckbox2">Mar</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Wed">
            <label class="form-check-label" for="inlineCheckbox2">Mie</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Thu">
            <label class="form-check-label" for="inlineCheckbox2">Jue</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Fri">
            <label class="form-check-label" for="inlineCheckbox2">Vie</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="Sat">
            <label class="form-check-label" for="inlineCheckbox2">Sab</label>
          </div>          
      </div>

        <label class="mb-1">Horarios:</label>
        <div class="row">
          <div class="col-6"><input class="form-control" type="number" name="" min="700" max="1900" id="hourStart"></div>
          <div class="col-6"><input class="form-control" type="number" name="" min="" max="2000" id="hourEnd"></div>
        </div>
        

        <label class="mb-1">Cupos disponibles(aqui la capacidad maxima):</label>
            <input type="number" id="available_spaces" name="cupo"  placeholder="Select..." value="" min="5" max="100" step="1">
        <div class="alert alert-danger" role="alert" id="alertCapacity">
          Capacidad incorrecta.
        </div>   
        <button type="button" class="btn btn-success mt-4 btn-sm" id="btnNewSection">Enviar</button>
        </div>

      
      </div>
    </div>
  </div>
</div>

<div class="alert alert-success successAlert" hidden id="alertDelete" role="alert">
    Sección eliminada.
</div>

<div class="modal fade" id="deleteSectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar seccion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row p">
            <div class="col-9 flex justify-center items-center">
                <input type="text" id="inputSection" class="w-full" placeholder="Ingrese el ID de la seccion">
            </div>
            <div class="col-3">
                <button id="btnSearcSection" class="btn bg-custom-primary text-white">Buscar</button>
            </div>
        </div>
        <div id="sectionBody" class="pt-3">
        <table class="table w-100 bg-aux mt-2" id="tableDeleteSection" border="1" style="width: 50%; border-collapse: collapse;">
        <thead>
                <tr>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Seccion ID</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Hora inicio</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Hora fin</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Dias</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Aula</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Matriculados</th>
                  <th style="text-align: center;" scope="col" class="bg-aux text">Accion</th>
                </tr>
              </thead>
              
              <tbody>
               
                
              </tbody>
            </table>
            <div class="alert alert-danger" hidden role="alert"  id="alertIdsection">
              No existe un Aula con este ID
            </div>
        </div>
     

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Seccion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <div class="container  mt-2">
        <table class="table w-100 bg-aux mt-2" id="tableSecction"> 
            <tbody>
                
               
            </tbody>
        </table>
        <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Justification</label>
        <textarea class="form-control" id="justificationInput" rows="3"></textarea>
        <table class="table w-100 bg-aux mt-2" id="tableSecctionStudent"> 
            <tbody>
                
               
            </tbody>
        </table>
        <div class="alert alert-danger mt-2"  id="alertJustication" role="alert">
          Se necesita una justificacion
        </div>
        <div class="alert alert-success  mt-2"  id="validedQuotas" role="alert">
          Cupos Actualizados correctamente
        </div>
        <div class="alert alert-danger  mt-2"  id="invalidQuotas" role="alert">
          Error, cupos no validos
        </div>
      </div>
      </div>
      <div>
        <button type="button" class="btn btn-secondary pr-2" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="updateQuotas">Actualizar cupos</button>
        <button type="button" class="btn btn-danger" id="saveDeleteSection"><i class="bi bi-trash"></i></button>
      </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="waitlistModal" tabindex="-1" aria-labelledby="waitlistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="waitlistModalLabel">View Wait Lists</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="waitlistForm">
                    <div class="mb-3">
                        <label for="classCodeInput" class="form-label">Enter Class Code:</label>
                        <input type="text" id="classCodeInput" class="form-control" maxlength="20" required>
                    </div>
                    <button type="button" id="searchWaitlistBtn" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal to show waitlist search results -->
<div class="modal fade" id="resultsModal" tabindex="-1" aria-labelledby="resultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="resultsModalLabel">Waitlist Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resultsModalBody">
                <!-- Aquí se generará la tabla -->
            </div>
        </div>
    </div>
</div>


    <div class="container-fluid row h-full"> 
<div class="offcanvas offcanvas-end bg p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Chats <button class="btn mx-2" id="refreshChats"><i  id="refreshIcon" class="bi bi-arrow-clockwise text rotate"></i></button></h5>
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
                  <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseStudents" role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Opciones
                    </div>
                  </a>
                          <div class="list-group-parent">
                          <div class="list-group">
                  <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseStudents" role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                       Estudiantes
                      </div>
                    </a>
                          <div class="collapse" id="collapseStudents">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                               Ver hitorial
                            </button>
                            <a href="#" class="list-group-item list-group-item-action bg list-group-item-indent">View Requests</a>
                          </div>
                    <a class="text bg aux text-decoration-none my-2" data-bs-toggle="collapse" href="#collapseTeachers" role="button" aria-expanded="false" aria-controls="collapseTeachers">
                      <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Docentes
                      </div>
                    </a>
                          <div class="collapse" id="collapseTeachers">
                            <button type="button" id="changePassword" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#changePassword">
                               Cambiar Contraseña
                            </button>
                          </div>
                    <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseClasses" role="button" aria-expanded="false" aria-controls="collapseClasses">
                      <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Secciones
                      </div>
                    </a>
                          <div class="collapse" id="collapseClasses">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent"  id="newSectionClass">
                              Crear sección
                             </button>
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#waitlistModal">
                               Lista de espera
                            </button>
                          </div>
                          <div class="collapse" id="collapseClasses">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent"  id="deleteSection" data-bs-toggle="modal" data-bs-target="#deleteSectionModal">
                                Actualizar Sección
                             </button>
                          </div>
                </div>
                            
                          </div>
                    
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
                          <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                          <li><a class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">Chats</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                      </ul>
                   </div>
              </div>
          </div>
  
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text"><?php echo $role; ?></h4>
               
            </div>
            <?php include '../../../../src/components/teacherClasses.php'; ?>
            
        </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/departametHead.js"></script>
</body>
</html>