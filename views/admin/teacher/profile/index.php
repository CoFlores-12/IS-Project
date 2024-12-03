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
        input {
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Modal Upload Photo -->
    <div class="modal fade" id="uploadPhotoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="staticBackdropLabel">Add Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/api/post/students/photo.php" method="POST" enctype="multipart/form-data">
                        <?php echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">'; ?>
                        <input type="text" name="photoUrl" placeholder="Enter photo url">
                        <p class="mt-3">or</p>
                        <input type="file" name="upload" accept="image/*" id="upload">
                        <button type="submit" class="btn bg-custom-primary mt-3 text-white">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal upload photo -->

    <div class="container pt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/views//students/home/index.php">Mi campus</a></li>
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
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">

                                            <?php
                                                $photos = json_decode($student['photos'], true);
                                                for ($i=0; $i < 3; $i++) { 
                                                    if ($i === 0) {
                                                        echo '<div class="carousel-item active relative">';
                                                    } else {
                                                        echo '<div class="carousel-item relative">';
                                                    }
                                                    if (isset($photos["$i"])) { 
                                                        echo '<img src="/uploads/'.$photos["$i"].'" class="d-block w-100" alt="...">';
                                                        echo "<form class='d-inline deletePhotoForm'>";
                                                        echo '<input type="hidden" name="filename" value="'.$photos["$i"].'">';
                                                        echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">';
                                                        echo "<button name='delete' value='".$photos["$i"]."' class='btn btn-danger'><i class='bi bi-trash'></i></button>";
                                                        echo '</form>';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<img src="/public/images/upload.webp" class="d-block w-100" alt="...">';
                                                        echo "<button class='btn btn-primary'  data-bs-toggle='modal' data-bs-target='#uploadPhotoModal'><i class='bi bi-plus'></i></button>";
                                                        echo '</div>';
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h4><?php echo $student['first_name'].' '.$student['last_name'] ?></h4>
                                    <p class="text-secondary mb-1"><?php echo $student['career_name'] ?></p>
                                    <p class="text-secondary text-xs mb-1"><?php echo $student['faculty_name'] ?></p>
                                    <?php
                                        if (!$isMyUser) {
                                            echo '<button class="btn btn-primary">Add to contacts</button><button class="btn btn-outline-primary">Message</button>';
                                        }
                                    ?>
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
                                    <input id="fullName" type="text" readonly data-key="fullName" value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo Personal</h6>
                                </div>
                                <div class="col text-secondary">
                                    <input id="personalEmail" type="text" readonly class="readonly-input"  data-key="personalEmail" value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo Institucional</h6>
                                </div>
                                <div class="col text-secondary">
                                    <input id="instituteEmail" type="text" readonly  data-key="email" value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Número de teléfono</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="phone" type="text" readonly class="readonly-input"  data-key="phone" value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Dirección</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="address" type="text" readonly class="readonly-input"  data-key="direction" value="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Género</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="gender" type="text" readonly class="readonly-input" value="">
                                 </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Fecha de Nacimiento</h6>
                                 </div>
                                 <div class="col-sm-9 text-secondary">
                                      <input id="birthday" type="text" readonly class="readonly-input" value="">
                                  </div>
                            </div>
                            <hr>
                            <div class="row">
                                  <div class="col-sm-3">
                                      <h6 class="mb-0">Nacionalidad</h6>
                                 </div>
                                <div class="col-sm-9 text-secondary">
                                    <input id="nationatility" type="text" readonly class="readonly-input" value="">
                                   </div>
                           </div>
                            <div class="row">
                                <div class="col-sm-12" id="buttons-group">
                                    <?php
                                        if ($isMyUser) {
                                            echo '<a id="btnEditProfile" class="btn btn-info" target="__blank">Edit</a> 
                                                  <button id="cancelButton" class="btn text-white btn-danger hidden">Cancel</button> 
                                                  <button id="saveButton" class="btn text-white btn-primary hidden">Save</button>
                                                  <button id="LoadingButton" class="btn btn-primary hidden" type="button" disabled>
                                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                      Loading...
                                                  </button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gutters-sm">
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-aux h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3">Información Adicional</h6>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Número de Identidad</h6>
                                        </div>
                                        <div class="col text-secondary">
                                            <input id="idNumber" type="text" readonly  value="">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">País</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input id="country" type="text" readonly class="readonly-input" value="">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Departamento</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input id="department" type="text" readonly class="readonly-input" value="">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Municipalidad</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input id="municipality" type="text" readonly class="readonly-input" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-aux h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3">Histórico de Clases Asignadas</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/js/theme.js"></script>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
