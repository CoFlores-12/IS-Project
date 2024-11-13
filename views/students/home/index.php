<?php 
session_start();

$role = $_SESSION['role'];
$_SESSION['request'] = 'student';

include '../../../src/components/sessionValidation.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.1.0">
    <meta name="date" content="01/11/2014">
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/homeStudents.css">
</head>
<body>

<!-- Modal Requests -->
<div class="modal fade" id="modalRequests" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Create Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select name="requestType" class="form-control mb-2" id="requestType">
            <option value="">Select request type</option>
            <option value="1">Remedial Exam Fee</option>
            <option value="2">Class Cancellation</option>
            <option value="3">Career Change</option>
            <option value="4">Campus Change</option>
        </select>
        <div id="dataForRequest">
            
        </div>
    </div>
    </div>
  </div>
</div>

<!-- Modal Enrollment -->
<div class="modal fade" id="modalEnrolment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg">
          <div class="modal-body">
            <div class="row relative">
                <ul class="nav nav-tabs w-full">
                    <li class="nav-item">
                        <button id="addEnrollmentBtn" class="nav-link  bg-aux active  text " aria-current="page">Add class</button>
                    </li>
                    <li class="nav-item">
                        <button id="cancelEnrollmentBtn" class="nav-link text">Cancel class</button>
                    </li>
                </ul>
                <button type="button" class="btn-close absolute right-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
        <div id="form-data">
            <center><div class="spinner-border text m-4" role="status"></div></center>
        </div>
    </div>
    </div>
  </div>
</div>
    <div class="main">
        <div class="offcanvas offcanvas-start bg" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header justify-between">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
                <button type="button" class="btn bg text" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <span id="btnModalRequests" class="cursor-pointer row p-2 rounded bg-aux">
                    Create Request
                </span>
                <span id="btnModalEnrollment" class="cursor-pointer row my-2 p-2 rounded bg-aux">
                    Enrollment
                </span>
            
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
                        <li><a class="dropdown-item" href="/views/students/profile/index.php?account_number=12">My profile</a></li>
                        <li><a class="dropdown-item" href="#">Messages</a></li>
                        <li><a class="dropdown-item" href="#">requests</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="courses-container">
            <h5 class="text-md font-bold pt-4 pl-4">Running Course</h5>
            <div class="courses pl-4 pr-4 pb-4">
                <div class="card card-course shadow">
                    <div class="card-body">
                        <div class="name w-full rounded p-2 bg-primary text-white mb-1">
                            IS-100
                        </div>
                        <span class=" font-bold text-md mb-2">UX Design</span>
                        <div class="pr">
                            <div class="progress mt-1">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                            <p class="font-light text-xs">Progress (75%)</p>
                        </div>
                    </div>
                </div>
                <div class="card card-course shadow">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-success text-white mb-1">
                            IS-200
                        </div>
                        <span class="font-bold text-md mb-2">Illustration</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100" style="width: 47%"></div>
                        </div>
                        <p class="font-light text-xs">Progress (47%)</p>
                    </div>
                </div>
                <div class="card card-course  shadow">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-warning text-white mb-1">
                            IS-300
                        </div>
                        <span class="font-bold text-md mb-2">3D Modeling</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
                        </div>
                        <p class="font-light text-xs">Progress (30%)</p>
                    </div>
                </div>
                <div class="card card-course  shadow">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-orange-500 text-white mb-1">
                            IS-400
                        </div>
                        <span class="font-bold text-md mb-2">Programming</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-orange-500 progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
                        </div>
                        <p class="font-light text-xs">Progress (20%)</p>
                    </div>
                </div>
                <div class="card card-course shadow">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-danger text-white mb-1">
                            IS-500
                        </div>
                        <span class="font-bold text-md mb-2">Software</span>
                        <div class="progress mt-1">
                            <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
                        </div>
                        <p class="font-light text-xs">Progress (25%)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/homeStudent.js"></script>
</body>
</html>