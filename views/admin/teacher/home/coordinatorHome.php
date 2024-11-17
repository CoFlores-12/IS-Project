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

<div class="modal fade" id="careerChange" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Career Change</h1>
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
        <div id="careerChangeBody">
          <table class="my-2 table">
            <tbody >
              <p class="card-text placeholder-glow">
                <span class="placeholder col-7"></span>
                <span class="placeholder col-4"></span>
                <span class="placeholder col-4"></span>
                <span class="placeholder col-6"></span>
                <span class="placeholder col-8"></span>
              </p>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="careerChangeDataHeader"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDataBody">
              <p class="card-text placeholder-glow">
                <span class="placeholder col-7"></span>
                <span class="placeholder col-4"></span>
                <span class="placeholder col-4"></span>
                <span class="placeholder col-6"></span>
                <span class="placeholder col-8"></span>
              </p>
       
      </div>
    </div>
  </div>
</div>

<div class="main">
        <div class="offcanvas offcanvas-start bg" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="list-group">
                  <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseStudents" role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Requests
                      </div>
                    </a>
                          <div class="collapse" id="collapseStudents">
                            <button id="careerChangeBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent">
                                Career change
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
                        <li><a class="dropdown-item" href="#">My profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text"><?php echo $role; ?></h4>
               
            </div>
           
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/coordinator.js"></script>
</body>
</html>