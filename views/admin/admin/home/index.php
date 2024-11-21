<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Administrator';

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
                    <input name="name" type="text" class="form-control" id="validationCustom01" placeholder="Names"  required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="lastName" type="text" class="form-control" id="validationCustom02" placeholder="Surnames" required>
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
            <div class="row mb-4">
                <div class="form-group">
                    <select class="for-control bg-aux w-full p-2" name="departament" id="departamentSelect">
                    <option value="">Select department...</option>
                    </select>
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

<!-- Modal SRP -->
<div class="modal fade" id="SRP" tabindex="-1" role="dialog" aria-labelledby="SRP" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    
  </div>
</div>
<!-- Modal SRP -->

<!-- Modal for Role Administration -->
<div class="modal fade" id="roleAdminModal" tabindex="-1" aria-labelledby="roleAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg">
            <div class="modal-header">
                <h5 class="modal-title" id="roleAdminModalLabel">Role Administration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover text">
                    <thead>
                        <tr>
                            <th>Person ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="roleAdminTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal to edit Roles -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    <div class="mb-3">
                        <label for="roleDropdown" class="form-label">Select New Role</label>
                        <select id="roleDropdown" class="form-select"></select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExceptionalCancellation" tabindex="-1" role="dialog" aria-labelledby="SRP" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Exceptional class cancellation period</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <center>
                    <label for="start-time">Start time</label><br>
                    <input
                        type="datetime-local"
                        id="start-time-exceptional"
                        name="start-time"/>
                </center>
            </div>
            <div class="col-6">
                <center>
                    <label for="end-time">End time</label><br>
                    <input
                        type="datetime-local"
                        id="end-time-exceptional"
                        name="end-time"/>
                </center>

            </div>
        </div>
        <div class="row">
            <center>
                <button id="saveECCBtn" type="button" class="btn bg-custom-primary m-4 text-white">Save</button>
            </center>
        </div>
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
                  <a class="text bg aux text-decoration-none" data-bs-toggle=""  role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Settings
                      </div>
                    </a>
                          <div class="" id="collapseStudents">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                                enrollment
                            </button>
                            <button id="btnExceptionalCancellation" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#modalExceptionalCancellation">
                                exceptional cancellation
                            </button>
                            <button id="roleAdministrationBtn" type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#roleAdminModal">
                                role administration
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
            <div class=" flex px-4">
                <h4 class="text">Dashboard</h4>
                
            </div>
            <div class="row p-2">
                <div class="col">
                    <div class="card bg-aux shadow rounded m-2  p-2">
                        <span>Teachers</span>
                        <div id="Teachers"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Students</span>
                        <div id="Students"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Careers</span>
                        <div id="Careers"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Regional Center</span>
                        <div id="RegionalCenter"><p class="card-text placeholder-glow"><span class="placeholder col-6"></span></p></div>
                    </div>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Admissions period</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Start time</label><br>
                                        <div id="inputStartR">
                                        <p class="card-text placeholder-glow"><span class="placeholder col-12"></span></p>
                                        </div>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">End time</label><br>
                                        <div id="inputEndR">
                                        <p class="card-text placeholder-glow"><span class="placeholder col-12"></span></p>
                                        </div>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Save</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Exceptional cancellation period</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Start time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="start-time"
                                            class="form-control"
                                            name="start-time"/>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">End time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="end-time"
                                            class="form-control"
                                            name="end-time"/>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Save</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 my-2">
                    <div class="card bg-aux">
                        <div class="card-body">
                            <h5 class="modal-title" id="exampleModalCenterTitle">period</h5>
                            <div class="row my-3">
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="start-time">Start time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="start-time"
                                            class="form-control"
                                            name="start-time"/>
                                    </center>
                                </div>
                                <div class="col-12 col-md-6">
                                    <center>
                                        <label for="end-time">End time</label><br>
                                        <input
                                            type="datetime-local"
                                            id="end-time"
                                            class="form-control"
                                            name="end-time"/>
                                    </center>

                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <button id="saveSRPBtn" type="button" class="btn bg-custom-primary mt-2 text-white">Save</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row p-2">
                <div class=" col-12">
                    <div class="card bg-aux">

                        <div class="card-body">
                            <div class="flex flex-row justify-between items-center">
                                <h5 class="text">Users</h5>
                                <button id="addUserBtn" class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#newUserModal">Add User</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/roleAdministration.js"></script>
    <script>
        
    </script>
</body>
</html>
