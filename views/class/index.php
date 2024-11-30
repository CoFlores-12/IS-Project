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
</head>
<body>
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
                    <div class="mb-4">
                        <h4 id="section-title" class="text">Información General</h4>
                        <p class="text">Estudiantes, bienvenidos a este curso.</p>
                    </div>

                    <!-- Subsections -->
                    <div class="row">
                        <!-- Students Table -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 bg">
                                <div class="card-body">
                                    <h5 class="card-title">Lista de Estudiantes</h5>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Juan Pérez</td>
                                                <td>Activo</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>María López</td>
                                                <td>Activo</td>
                                            </tr>
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
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="YouTube video" allowfullscreen></iframe>
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
</body>
</html>
