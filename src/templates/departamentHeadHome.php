
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

<div class="modal fade" id="historyStudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg  modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">View History</h1>
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
        <div id="historyBody">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p">
            <div class="col-9 flex justify-center items-center">
                <input type="text" id="inputTeacher" class="w-full" placeholder="Enter the employee number, identification or institutional email of the teacher">
            </div>
            <div class="col-3">
                <button id="btnSearcTeacher" class="btn bg-custom-primary text-white">Search</button>
            </div>
        </div>
        <div id="resetBody">

        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="newSection" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bg modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add new section</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row p">
      
        <label class="mb-1">Classes:</label>
        <select class="form-select mb-3" id="classes">
            <option value="" selected>Select...</option>
        </select>

        <label class="mb-1">Teachers:</label>
        <select class="form-select mb-3" id="teachers">
            <option value="" selected>Select...</option>
        </select>

        <label class="mb-1">Classrooms:</label>
        <select class="form-select mb-3" id="classrooms">
            <option value="" selected>Select...</option>
        </select>

        <label class="mb-1">Schedule:</label>
        <select class="form-select mb-3" id="schedule">
                <option value="" selected>Select...</option>
                <option value="07:00ini-08:00fin">07:00ini-08:00fin</option>
                <option value="08:00ini-09:00fin">08:00ini-09:00fin</option>
                <option value="09:00ini-10:00fin">09:00ini-10:00fin</option>
                <option value="10:00ini-11:00fin">10:00ini-11:00fin</option>
                <option value="11:00ini-12:00fin">11:00ini-12:00fin</option>
                <option value="12:00ini-13:00fin">12:00ini-13:00fin</option>
                <option value="13:00ini-14:00fin">13:00ini-14:00fin</option>
                <option value="14:00ini-15:00fin">14:00ini-15:00fin</option>
                <option value="15:00ini-16:00fin">15:00ini-16:00fin</option>
                <option value="16:00ini-17:00fin">16:00ini-17:00fin</option>
                <option value="17:00ini-18:00fin">17:00ini-18:00fin</option>
                <option value="18:00ini-19:00fin">18:00ini-19:00fin</option>
                <option value="19:00ini-20:00fin">19:00ini-20:00fin</option>
        </select>

        <label class="mb-1">Available Spaces:</label>
            <input type="number" id="available_spaces" name="cupo" min="1" max="100" placeholder="Select..." value="">
            
        <button type="button" class="btn btn-success mt-4 btn-sm" id="btnNewSection">Success</button>
        </div>

        <div id="newClassBody">
                
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
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">Students</div>
                        <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                            View History
                        </button>
                        <a href="#" class="list-group-item list-group-item-action bg list-group-item-indent">View Requests</a>

                    <div class="list-group-item list-group-title list-group-item-primary text bg-aux fw-bold mt-2">Teachers</div>
                         <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#changePassword">
                            Reset Password
                        </button>
                </div>
                <div class="list-group-item list-group-title list-group-item-primary text bg-aux fw-bold mt-2">Classes</div>
                         <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#newSection" id="newSectionClass">
                             Add new section
                        </button>
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
                <h4 class="text"><?php echo $role; ?></h4>
               
            </div>
           
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/departametHead.js"></script>
</body>
</html>