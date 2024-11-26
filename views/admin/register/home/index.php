<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Register Agent';

AuthMiddleware::checkAccess($requiredRole);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Home</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<div class="main">

        <div class="header p-2 text-inverter bg">
            <div class="flex justify-between">
                <button class="btn bg text" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    
                </button>
                
                <div class="btn-group">
                    <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                        
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">My profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Registro</h4>
               
            </div>
            <div class="row m-4 ">
            <div class="card col-12 col-md-6 shadow bg tex">
                <div class="card-body">
                        <h4>Crear Estudiantes</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="my-3">
                                    <label for="formFile" class="form-label">Cargar el archivo CSV con los datos de los proximos estudiantes</label>
                                    <input class="form-control"  accept=".csv" type="file" id="csvFile">
                                </div>
                                    <button type="button" class="btn btn-success mt-4 btn-sm" id="sendCSV">Enviar</button>
                            </div>
                            
                           
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/createNewStudy.js"></script>
    
</body>
</html>