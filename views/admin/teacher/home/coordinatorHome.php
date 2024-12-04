<?php
require '../../../../src/modules/Auth.php';

$requiredRole = 'Coordinator';

AuthMiddleware::checkAccess($requiredRole);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator | Home</title>
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
        
        </style>
</head>
<body>
<div class="modal fade" id="academicModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered" role="document">
    <div class="modal-content bg">
      <div class="modal-header">
        <h5 class="modal-title">Carga académica</h5>
      </div>
      <div class="modal-body" id="academicBody">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="downloadExcel()">Descargar en hoja de calculo</button>
        <button type="button" class="btn btn-danger" onclick="downloadPDF()">Descargar en PDF</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="careerChange" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Solicitudes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="container modal-body">
        <div class="row mb-2 flex items-center">
                <label for="filter-select" class="form-label m-0 w-content">Filtrar por tipo de solicitud:</label>
                <select id="filter-select" class="form-select w-content" onchange="filterRequests()">
                    <option value="">Todos los tipos</option>
                </select>
        </div>

        <div class="table-responsive">
            <table id="requests-table" class="table bg table-bordered table-striped">
                <thead class="bg-aux">
                    <tr>
                      <th>Fecha</th>
                      <th>Estudiante ID</th>
                      <th>Tipo</th>
                      <th>Período</th>
                    </tr>
                </thead>
                <tbody id="tableRequest">
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
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
<div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg  ">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="careerChangeDataHeader">solicitud</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDataBody">
      </div>
    </div>
  </div>
</div>


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
                  
                <button id="careerChangeBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent">
                    Ver solicitudes
                </button>
                <button id="academicBtn" type="button" class="text my-2 list-group-item list-group-item-action bg list-group-item-indent">
                    Ver carga académica
                </button>

                <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                    Ver historial
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
                          <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                          <li><a class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">Chats</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                      </ul>
                   </div>
              </div>
          </div>
  
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Coordinador</h4>
               
            </div>
            <?php include '../../../../src/components/teacherClasses.php'; ?>
            
        </div>
</div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/coordinator.js"></script>
</body>
</html>