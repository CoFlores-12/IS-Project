<?php
$requiredRole = 'Teacher';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher | Home</title>
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
    </style>
</head>
<body>


<div class="main">
<div class="offcanvas offcanvas-end bg p-0" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Mensajeria <button class="btn mx-2" id="refreshChats"><i  id="refreshIcon" class="bi bi-arrow-clockwise text rotate"></i></button></h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="body h-full" id="bodyOffCanvas">
                <iframe id="frameChats" src="/views/chats/index.php" class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div>
        <div class="header p-2 text-inverter bg">
            <div class="flex justify-between">
                <button class="btn bg text" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    
                </button>
                
                <div class="btn-group">
                    <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                        
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li><a class="dropdown-item text" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">Mensajeria</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Salir <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Docente</h4>
               
            </div>
            <?php include '../../../../src/components/teacherClasses.php'; ?>
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>