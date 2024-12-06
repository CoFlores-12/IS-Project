<?php 
include '../../src/modules/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href = '/views/admin/login/index.php?error=401';</script>";
    exit;
}
$userRole = $_SESSION['user']['role'];
$roles = ["Department Head", "Coordinator", "Teacher"];


date_default_timezone_set('America/Tegucigalpa');
$db = (new Database())->getConnection();
$result = $db->execute_query("SELECT 
    CONCAT(p.first_name, ' ', p.last_name) as teacher,
    s.employee_number
FROM Section s
INNER JOIN Employees em ON s.employee_number = em.employee_number
INNER JOIN Persons p ON em.person_id = p.person_id
WHERE s.section_id = ?
 ", [$_GET['section_id']]);

$row = $result->fetch_assoc();

//TODO mostrar u ocultar botón nuevo video depende el rol y si ya tiene un video subido

//TODO validar quien accede a la clase

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clase</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
    #boton {
        z-index: 10;
        position: relative;
        cursor: pointer;
    }

    /* Contenedor general con Flexbox */
    .container {
        display: flex;
        justify-content: space-between; /* Para alinear los elementos uno al lado del otro */
        gap: 10px;
        flex-wrap: wrap; /* Para permitir que los elementos se ajusten en pantallas más pequeñas */
    }

    /* Estilos para el contenedor de información general */
    .info-container {
        flex: 1; /* Ocupa todo el espacio disponible */
    }

    /* Estilos para el contenedor de la tarjeta del docente */
    .teacher-container {
        flex: 1; /* Ocupa todo el espacio disponible */
        display: flex;
        justify-content: center; /* Centra el contenido dentro del contenedor del docente */
        align-items: center; /* Alinea verticalmente el contenido */
    }

    /* Estilos para la tarjeta del docente */
    .teacher-profile-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        width: 250px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .teacher-profile-card:hover {
        background-color: #f1f1f1;
    }

    .teacher-card-header {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .teacher-card-body p {
        font-size: 14px;
        color: #555;
    }

    /* Se oculta el div por defecto */
    .d-none {
        display: none;
    }
       
        .list-group-title {
            border: none !important;
            font-weight: bold;
            outline: none;
        }
        .list-group-item-indent {
            border: none; 
        }
        table {
            width: 100%;
        }
    </style>

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
<div class="modal fade" id="modalVideo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar video introductorio de la clase</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <div class="container  mt-2">
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Ingrese la URL de su video</label>
            <input type="url" id="videoUrl" class="form-control mb-2" placeholder="Ingrese el enlace del video" required>
            <div class="alert alert-danger mt-2"  id="alertErrorVideo" role="alert">
                Error, algo salio mal. Vuelva a intentarlo
            </div>
            <div class="alert alert-success  mt-2"  id="validedVideo" role="alert">
                Video agregado Correctamente
            </div>
        </div>
        </div>
        <div>
            <button type="button" class="btn btn-secondary pr-2" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" id="saveVideo">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content bg">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Participantes</h5>
    </div>
    <div class="modal-body">
          <table class="table table-bordered table-hover">
              <thead class="bg-aux">
                  <tr>
                      <th class="bg-aux bg-custom-primary text-white">Docente</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><a href="/views/admin/teacher/profile/index.php?employee_number=<?php echo $row['employee_number'] ?>"><?php echo $row['teacher'] ?></a></td>
                  </tr>
              </tbody>
          </table>
          <h4>Estudiantes</h4>
          <table class="table table-bordered table-hover" id="studentsTable">
              <thead class="bg-aux">
                  <tr>
                      <th  class="bg-aux bg-custom-primary text-white">#</th>
                      <th  class="bg-aux bg-custom-primary text-white">Nombre</th>
                      <th  class="bg-aux bg-custom-primary text-white">Numero de cuenta</th>
                      <th  class="bg-aux bg-custom-primary text-white">Correo institucional</th>
                  </tr>
              </thead>
              <tbody id="students-table-body">
                <!-- Las filas serán generadas dinámicamente por JavaScript -->
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <?php
            if (in_array($userRole, $roles)) {
                echo '<button type="button" class="btn btn-success" onclick="downloadExcel()">Descargar Excel</button>
                <button type="button" id="downloadPdf" class="btn btn-danger" onclick="generatePdf()">Descargar PDF</button>';
            }
        ?>

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="scoresModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content bg modal-lg">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Calificaciones</h5>
      </div>


    <?php if ($userRole == "Student"): ?>
        
    <?php endif; ?>


    <div class="modal-body" id="bodyTableScores">
        <p class="card-text placeholder-glow">
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
        </p>
      </div>
      

    </div>
  </div>
</div>


    <div class="container-fluid row h-full">
        <!-- Offcanvas -->
        <div class="offcanvas offcanvas-end bg p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                    Chats
                    <button class="btn mx-2" id="refreshChats">
                        <i id="refreshIcon" class="bi bi-arrow-clockwise text rotate"></i>
                    </button>
                </h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="body h-full" id="bodyOffCanvas">
                <iframe id="frameChats" src="/views/chats/index.php" class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-3 d-none d-md-block bg-aux" id="desktopAside">
            <div class="offcanvas-header py-3 justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn d-md-none" aria-label="Close" onclick="toggleSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="list-group">
                
                <div class="list-group-parent">
                    <button id="participantsBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent"  data-toggle="modal" data-target="#participantsModal">
                        Participantes
                    </button>
                    <button id="scoresBtn" type="button" class="text my-2 list-group-item list-group-item-action bg list-group-item-indent">
                        Calificaciones
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col">
        <div class="alert alert-success mt-2" hidden  id="alertSendSurvey" role="alert">
                Evaluacion guardada Correctamente
            </div>
            <div class="header p-2 text-inverter bg">
                <div class="flex justify-between">
                    <button class="btn bg text" type="button" id="toggleAside">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img height="40px" width="40px" src="https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light" />
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                            <li>
                                <a class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                    Chats
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Section Container -->
            <div id="section-container">
                <div class="pl-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:history.back()">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="section-title"></li>
                    </ol>
                </nav>
                </div>
                <div class="rounded p-3">
                    <div class="col">
                        <div class="card shadow-sm border-0 bg-aux">
                            <div class="card-body">
                                <h5 class="card-title text">Material de Apoyo</h5>
                                <div id="butonVideo" class="m-2">

                                <?php if (in_array($userRole, $roles)): ?>
                                    <button class="btn btn-success" id="addVideo">Agregar video</button>
                                <?php endif; ?>
                                
                                </div>
                                <div id="video-container" class="ratio ratio-16x9">
                                    <img id="placeholder" src="/uploads/genericVideo.webp" alt="Video Introductorio" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <script src="/public/js/sectionController.js"></script>

</body>
</html>
