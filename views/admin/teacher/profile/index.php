<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        input {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container pt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/views/students/home/index.php">Mi campus</a></li>
                <li class="breadcrumb-item active" aria-current="page">Perfil</li>
            </ol>
        </nav>
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card bg-aux text">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="w-full">
                                    <img src="/public/images/upload.webp" class="d-block w-100" alt="Imagen de perfil">
                                </div>
                                <div class="mt-3">
                                    <h4 id="studentName">Nombre del Estudiante</h4>
                                    <p class="text-secondary mb-1" id="careerName">Carrera</p>
                                    <p class="text-secondary text-xs mb-1" id="facultyName">Facultad</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card bg-aux mb-3">
                        <div class="card-body">
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nombre Completo</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="fullName" type="text" readonly value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo Personal</h6>
                                </div>
                                <div class="col text-secondary">
                                    <input id="personalEmail" type="text" readonly value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo Institucional</h6>
                                </div>
                                <div class="col text-secondary">
                                    <input id="instituteEmail" type="text" readonly value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Número de teléfono</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="phone" type="text" readonly value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Rol</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="role" type="text" readonly value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Departamento</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="departament" type="text" readonly value="">
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-aux h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3">Histórico de Clases Asignadas</h6>
                                </div>
                                <div id="history" class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/js/teacherProfileController.js"></script>
    <script src="/public/js/theme.js"></script>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
