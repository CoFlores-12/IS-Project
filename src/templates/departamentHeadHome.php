
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departament Head | Home</title>
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
        
        <input type="file" name="" accept=".csv" id="file">
        <button type="button" class="btn bg-custom-primary mt-4 form-control text">upload file</button>
        <hr class="my-2">
        <button type="button" id="newSectionManualBtn" class="btn btn-primary mt-4 form-control">Manual</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="newSectionManual" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <div class="days">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Mon">
            <label class="form-check-label" for="inlineCheckbox1">Mon</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Tue">
            <label class="form-check-label" for="inlineCheckbox2">Tue</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Wed">
            <label class="form-check-label" for="inlineCheckbox2">Wed</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="Thur">
            <label class="form-check-label" for="inlineCheckbox2">Thur</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="Fri">
            <label class="form-check-label" for="inlineCheckbox2">Fri</label>
          </div>          
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="Sat">
            <label class="form-check-label" for="inlineCheckbox2">Sat</label>
          </div>          
      </div>

        <label class="mb-1">Schedule:</label>
        <div class="row">
          <div class="col-6"><input class="form-control" type="number" name="" min="700" max="1900" id="hourStart"></div>
          <div class="col-6"><input class="form-control" type="number" name="" min="" max="2000" id="hourEnd"></div>
        </div>
        

        <label class="mb-1">Available Spaces (maximum student capacity):</label>
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
                  <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseStudents" role="button" aria-expanded="false" aria-controls="collapseStudents">
                    <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Students
                      </div>
                    </a>
                          <div class="collapse" id="collapseStudents">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#historyStudent">
                                View History
                            </button>
                            <a href="#" class="list-group-item list-group-item-action bg list-group-item-indent">View Requests</a>
                          </div>
                    <a class="text bg aux text-decoration-none my-2" data-bs-toggle="collapse" href="#collapseTeachers" role="button" aria-expanded="false" aria-controls="collapseTeachers">
                      <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Teachers
                      </div>
                    </a>
                          <div class="collapse" id="collapseTeachers">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent" data-bs-toggle="modal" data-bs-target="#changePassword">
                               Reset Password
                            </button>
                          </div>
                    <a class="text bg aux text-decoration-none" data-bs-toggle="collapse" href="#collapseClasses" role="button" aria-expanded="false" aria-controls="collapseClasses">
                      <div class="list-group-item list-group-title list-group-item- bg-aux text fw-bold">
                        Classes
                      </div>
                    </a>
                          <div class="collapse" id="collapseClasses">
                            <button type="button" class="text list-group-item list-group-item-action bg list-group-item-indent"  id="newSectionClass">
                                   Add new section
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