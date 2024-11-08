<?php 
session_start();

$role = $_SESSION['role'];
$_SESSION['request'] = 'admin';

include '../../../../src/components/sessionValidation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Home</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>

<!-- Modal New User -->
<div class="modal fade" id="newUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">New Teacher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form class="needs-validation bg rounded p-4" novalidate method="POST" action="/api/post/admin/addUser.php"  enctype="multipart/form-data">
            <input type="text" name="role" hidden readonly value="Teacher">
            <div class="form-row flex gap-4">
                <div class="col mb-3">
                    <input name="name" type="text" class="form-control" id="validationCustom01" placeholder="First name"  required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="lastName" type="text" class="form-control" id="validationCustom02" placeholder="Last name" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <input name="identity" type="text" class="form-control" id="identity" placeholder="identity" maxlength="15" required>
                </div>
            </div>
            <div class="form-row flex gap-4">
                <div class="col mb-3">
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone Number" maxlength="9" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn bg-custom-primary text-white">Save</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal New User -->






<div class="main">
        <div class="offcanvas offcanvas-start bg" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title text" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                
                
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
                        <li><a class="dropdown-item" href="#">Messages</a></li>
                        <li><a class="dropdown-item" href="#">requests</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Dashboard</h4>
                <div class="buttons">
                    <button class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#newUserModal">Add User</button>
                </div>
            </div>
            <div class="row p-4">
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2  p-2">
                        <span>Teachers</span>
                        <strong>67</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Students</span>
                        <strong>1830</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Careers</span>
                        <strong>12</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Regional Center</span>
                        <strong>2</strong>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
    })();

    
    </script>
</body>
</html>