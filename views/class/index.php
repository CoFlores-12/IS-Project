<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseStudents" role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item bg-aux text fw-bold">
                        Solicitudes
                    </div>
                </a>
                <div class="list-group-parent">
                    <button id="careerChangeBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent">
                        Cambio de carrera
                    </button>
                    <button id="cancelBtn" type="button" class="text my-2 list-group-item list-group-item-action bg list-group-item-indent">
                        Cancelación excepcional
                    </button>
                    <button id="cBtn" type="button" class="text my-2 list-group-item list-group-item-action bg list-group-item-indent">
                        Cambio de centro
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
            <div id="section-container" class="p-3">
                <div class="rounded border shadow p-4 bg-aux">
                    <!-- General Information -->
                    <div class="container">
                        <div class="info-container">
                            <div class="mb-4">
                                <h4 id="section-title" class="text">Información General</h4>
                                <p class="text">Estudiantes, bienvenidos a este curso.</p>
                            </div>
                        </div>

                        <!-- Teacher Info (visible for teacher role) -->
                        <div class="teacher-container mb-4" id="teacher-profile">
                            <div class="teacher-profile-card bg">
                                <div class="teacher-card-header bg">
                                    Profesor(a): <span class="text" id="teacher-name"></span>
                                </div>
                                <div class="teacher-card-body bg">
                                    <a class="text" href="/profile">Ver mi perfil</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subsections -->
                    <div class="row">
                        <!-- Students Table -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 bg">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title text">Lista de Estudiantes</h5>
                                        <button id="downloadPdf" class="btn btn-success d-none mb-3">Descargar Lista de Estudiantes</button>
                                    </div>
                                    <table class="table table-bordered table-hover" id="studentsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Núm. Cuenta</th>
                                                <th>Correo Institucional</th>
                                            </tr>
                                        </thead>
                                        <tbody id="students-table-body">
                                            <!-- Filas dinámicas generadas desde el JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Video Embed -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 bg">
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
    </div>

    <script src="/public/js/sectionController.js"></script>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</body>
</html>
