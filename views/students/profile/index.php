<?php
$idTest = 12;
$studentId = $_GET['studentId'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="main-body">
              <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                  <div class="card bg-aux text">
                    <div class="card-body">
                      <div class="d-flex flex-column align-items-center text-center">
                        <div class="w-full">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="d-block w-100" alt="...">
                                </div>
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
                          <h4>John Doe</h4>
                          <p class="text-secondary mb-1">Software Engineer</p>
                          <?php
                            if ($idTest != $studentId) {
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
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Student ID</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input" data-key="id" value="<?php echo $studentId ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input" data-key="fullName" value="John Doe">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="email" data-key="id" value="johndoe@unah.hn">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Phone</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="phone" value="3487-9723">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="direction" value="Tegucigalpa, HN">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-12" id="buttons-group">
                          <?php
                            if ($studentId == $idTest) {
                              echo '<a id="btnEditProfile" class="btn btn-info" target="__blank">Edit</a> <button id="saveButton" class="btn text-white btn-primary hidden">Save</button>';
                              
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
                          <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2"></i>Project Status</h6>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <div class="card bg-aux h-100">
                        <div class="card-body">
                          <h6 class="d-flex align-items-center mb-3">Project Status</h6>
                          
                        </div>
                      </div>
                    </div>
                  </div>
    
    
    
                </div>
              </div>
    
            </div>
        </div>
    </div>
    
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

    <script>
      let buttonsGroup = document.getElementById('buttons-group'); 
      let btnEditProfile = document.getElementById('btnEditProfile'); 
      let saveButton = document.getElementById('saveButton'); 
      
      btnEditProfile.addEventListener('click', (e)=>{
        let inputs = document.querySelectorAll('.readonly-input');
        btnEditProfile.classList.add('hidden');
        inputs.forEach(input => {
          input.readOnly = false;
          input.classList.add('bg');
        });
        saveButton.classList.remove('hidden');
      })

      saveButton.addEventListener('click', function() {
            const inputs = document.querySelectorAll('input[data-key]');
            const dataKeyValues = Array.from(inputs).reduce((acc, input) => {
                acc[input.dataset.key] = input.value;
                return acc;
            }, {});

            console.log(dataKeyValues);
        });

    </script>
</body>
</html>
