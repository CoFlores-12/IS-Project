<?php 

//TODO obtener docente y estudiantes y renderizar en tabla 

//TODO mostrar u ocultar botón nuevo video depende el rol y si ya tiene un video subido

//TODO obtener información de la clase y renderizar

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

<div class="modal fade" id="modalVideo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
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
                      <th class="bg-aux">Docente</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>Juan Pérez</td>
                  </tr>
              </tbody>
          </table>
          <table class="table table-bordered table-hover">
              <thead class="bg-aux">
                  <tr>
                      <th  class="bg-aux">Estudiantes</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>Juan Pérez</td>
                  </tr>
                  <tr>
                      <td>María López</td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Descargar Excel</button>
        <button type="button" class="btn btn-danger">Descargar PDF</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="scoresModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Calificaciones</h5>
      </div>
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
      <div class="modal-footer">
        <button type="button" class="btn bg-custom-primary text-white">Guardar</button>
        <button type="button" class="btn btn-danger text-white">Finalizar</button>
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
                        <li class="breadcrumb-item"><a href="">Inicio</a></li>
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
    <script src="/public/js/sectionController.js"></script>
</body>
</html>
