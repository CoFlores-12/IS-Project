<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Administrator';

AuthMiddleware::checkAccess($requiredRole);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Inicio</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/homeAdmin.css">
</head>
<body>

<div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logModalLabel">Registros de Acceso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-6">
            <label for="authStatusFilter" class="form-label">Filtrar por estado de autenticación</label>
            <select id="authStatusFilter" class="form-select">
              <option value="">Todos</option>
              <option value="1">Éxito</option>
              <option value="0">Fallo</option>
            </select>
          </div>
          <div class="col-6">
            <label for="roleFilter" class="form-label">Filtrar por Rol</label>
            <select id="roleFilter" class="form-select">
              <option value="">Todos</option>
              <option value="0">Administrador</option>
              <option value="1">Admisiones</option>
              <option value="2">Registro</option>
              <option value="3">Docentes</option>
              <option value="4">Coordinador</option>
              <option value="7">Estudiantes</option>
            </select>
          </div>
        </div>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>IP Address</th>
              <th>Estado de Autenticación</th>
              <th>Identificador</th>
              <th>Role</th>
            </tr>
          </thead>
          <tbody id="logTableBody">
          </tbody>
        </table>

        
      </div>
      <div class="modal-footer row justify-between items-center">
        <div class="d-flex justify-content-between align-items-center">
          <select id="rowsPerPage" class="form-select w-auto">
            <option value="5">5 filas</option>
            <option value="10">10 filas</option>
            <option value="20">20 filas</option>
          </select>
          <nav>
            <ul class="pagination m-0" id="pagination">
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal New User -->
<div class="modal fade" id="newUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Nuevo usuario</h5>
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
            <div class="row mb-4">
                <div class="form-group">
                    <select class="form-control bg-aux w-full p-2" required  name="departament" id="departamentSelect">
                        <option value="">Seleccione un departamento...</option>
                    </select>
                    <div id="mainCareerFeedback" class="invalid-feedback">Por favor seleccione un departamento.</div>
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

<!-- Modal SRP -->
<div class="modal fade" id="SRP" tabindex="-1" role="dialog" aria-labelledby="SRP" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    
  </div>
</div>
<!-- Modal SRP -->

<!-- Modal for Role Administration -->
<div class="modal fade" id="roleAdminModal" tabindex="-1" aria-labelledby="roleAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg">
            <div class="modal-header">
                <h5 class="modal-title" id="roleAdminModalLabel">Role Administration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover text">
                    <thead>
                        <tr>
                            <th>Person ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="roleAdminTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal to edit Roles -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    <div class="mb-3">
                        <label for="roleDropdown" class="form-label">Select New Role</label>
                        <select id="roleDropdown" class="form-select"></select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExceptionalCancellation" tabindex="-1" role="dialog" aria-labelledby="SRP" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Exceptional class cancellation period</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <center>
                    <label for="start-time">Start time</label><br>
                    <input
                        type="datetime-local"
                        id="start-time-exceptional"
                        name="start-time"/>
                </center>
            </div>
            <div class="col-6">
                <center>
                    <label for="end-time">End time</label><br>
                    <input
                        type="datetime-local"
                        id="end-time-exceptional"
                        name="end-time"/>
                </center>

            </div>
        </div>
        <div class="row">
            <center>
                <button id="saveECCBtn" type="button" class="btn bg-custom-primary m-4 text-white">Save</button>
            </center>
        </div>
      </div>
      
    </div>
  </div>
</div>

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
        <div class="offcanvas offcanvas-start bg" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="list-group">
                  <a class="text bg aux text-decoration-none" data-bs-toggle=""  role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Settings
                      </div>
                    </a>
                          <div class="" id="collapseStudents">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                                enrollment
                            </button>
                            <button id="btnExceptionalCancellation" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#modalExceptionalCancellation">
                                exceptional cancellation
                            </button>
                            <button id="roleAdministrationBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#roleAdminModal">
                                role administration
                            </button>
                          </div>
                    
                    
                </div>
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
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Cerrar sesión <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex px-4">
                <h4 class="text">Administrador</h4>
                
            </div>
            <div class="row p-2">
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2  p-2">
                        <span>Docentes</span>
                        <div id="Teachers"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Estudiantes</span>
                        <div id="Students"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Carreras</span>
                        <div id="Careers"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Centros regionales</span>
                        <div id="RegionalCenter"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Periodo de admisiones</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Inicia</label><br>
                                        <div id="inputStartR">
                                        <p class="card-text placeholder-glow"><span class="placeholder col-12"></span></p>
                                        </div>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">Finaliza</label><br>
                                        <div id="inputEndR">
                                        <p class="card-text placeholder-glow"><span class="placeholder col-12"></span></p>
                                        </div>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Guardar</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Exceptional cancellation period</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Start time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="start-time"
                                            class="form-control"
                                            name="start-time"/>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">End time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="end-time"
                                            class="form-control"
                                            name="end-time"/>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Guardar</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">period</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Start time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="start-time"
                                            class="form-control"
                                            name="start-time"/>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">End time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="end-time"
                                            class="form-control"
                                            name="end-time"/>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Guardar</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row p-2">
                <div class=" col-12">
                    <div class="card bg-aux">

                        <div class="card-body">
                            <div class="flex flex-row justify-between items-center">
                                <h5 class="text">Usuarios</h5>
                                <button id="addUserBtn" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#newUserModal">Crear usuario</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class=" col-12">
                    <div class="card bg-aux">

                        <div class="card-body pb-8">
                            <div class="flex flex-row justify-between items-center">
                                <h5 class="text">Inicios de sesión</h5>
                                
                                <button id="addUserBtn" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#logModal">Mas detalles</button>
                            </div>
                            <div id="chart" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/roleAdministration.js"></script>
    
</body>
</html>
