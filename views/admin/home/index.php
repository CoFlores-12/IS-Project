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
                        <li><a class="dropdown-item" href="/">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class=" flex p-2 justify-between items-center">
                <h4 class="text">Dashboard</h4>
                <div class="vuttons">
                    <button class="btn bg-custom-primary text-white">Add User</button>
                </div>
            </div>
            <div class="row p-4">
                <div class="col">
                    <div class="card shadow rounded m-2 bg p-2">
                        <span>Teachers</span>
                        <strong>67</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg p-2">
                        <span>Students</span>
                        <strong>1830</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg p-2">
                        <span>Careers</span>
                        <strong>12</strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg p-2">
                        <span>Regional centers</span>
                        <strong>2</strong>
                    </div>
                </div>
            </div>

            <div class="row p-4">
                <div class="card rounded p-2">
                    <div class="card-header flex justify-between items-center">
                        <span>Admissions <span class="text-xxs text-secondary">(26/10/2024 - 30/10/2024)</span></span>
                        <a class="btn text-success btn-outline-success" href="/api/get/admin/admittedStudents.php">
                            Export <i class="bi bi-arrow-bar-up"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        List
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>