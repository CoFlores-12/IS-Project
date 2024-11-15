<?php
$idTest = 20201001034;
$account_number = $_GET['account_number'];
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Student';

AuthMiddleware::checkAccess($requiredRole);

include './../../../src/modules/database.php';

$db = (new Database())->getConnection();
$result = $db->execute_query("SELECT 
    A.person_id,
    A.account_number,
    A.institute_email,
    A.direction,
    A.photos,
    B.first_name,
    B.last_name,
    B.phone,
    B.personal_email,
    C.career_name,
    F.faculty_name
FROM Students A 
    INNER JOIN Persons B ON A.person_id = B.person_id 
    INNER JOIN Careers C ON A.career_id = C.career_id
    INNER JOIN Faculty F ON C.faculty_id = F.faculty_id
WHERE A.account_number = ?;", [$account_number]);
$student = $result->fetch_assoc() ?? null;

if (is_null($student)) {
  echo 'User not found!';
  return;
}

$isMyUser = $idTest == $account_number;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.1.0">
    <meta name="date" content="02/11/2014">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
      input {
        width: 100%;
      }
    </style>
</head>
<body>

<!-- Modal Upload Photo -->
<div class="modal fade" id="uploadPhotoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Add Photo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/api/post/students/photo.php" method="POST"  enctype="multipart/form-data">
          <?php echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">'; ?>
          <input type="text" name="photoUrl" placeholder="Enter photo url">
          <p class="mt-3">or</p>
          <input type="file" name="upload" accept="image/*" id="upload">
          <button type="submit" class="btn bg-custom-primary mt-3 text-white">Upload</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal upload photo -->

    <div class="container pt-3">
        <div class="main-body">
              <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                  <div class="card bg-aux text">
                    <div class="card-body">
                      <div class="d-flex flex-column align-items-center text-center">
                        <div class="w-full">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                            <?php

                              $photos = json_decode($student['photos'], true);
                              for ($i=0; $i < 3; $i++) { 
                                if ($i === 0) {
                                    echo '<div class="carousel-item active relative">';
                                } else {
                                    echo '<div class="carousel-item relative">';
                                }
                                if (isset($photos["$i"])) { 
                                  echo '<img src="/uploads/'.$photos["$i"].'" class="d-block w-100" alt="...">';
                                  echo "<form class='d-inline deletePhotoForm'>";
                                  echo '<input type="hidden" name="filename" value="'.$photos["$i"].'">';
                                  echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">';
                                  echo "<button name='delete' value='".$photos["$i"]."' class='btn btn-danger'><i class='bi bi-trash'></i></button>";
                                  echo '</form>';
                                  echo '</div>';
                                }else {
                                  echo '<img src="/public/images/upload.webp" class="d-block w-100" alt="...">';
                                  echo "<button class='btn btn-primary'  data-bs-toggle='modal' data-bs-target='#uploadPhotoModal'><i class='bi bi-plus'></i></button>";
                                  echo '</div>';

                                }
                              }
                            ?>
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
                          <h4><?php echo $student['first_name'].' '.$student['last_name'] ?></h4>
                          <p class="text-secondary mb-1"><?php echo $student['career_name'] ?></p>
                          <p class="text-secondary text-xs mb-1"><?php echo $student['faculty_name'] ?></p>
                          <?php
                            if (!$isMyUser) {
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
                          <h6 class="mb-0">Account Number</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="hidden" readonly data-key="person_id" value="<?php echo $student['person_id'] ?>">
                            <input type="text" readonly data-key="account_number" value="<?php echo $account_number ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly data-key="fullName" value="<?php echo $student['first_name'].' '.$student['last_name'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Personal Email</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="personalEmail" value="<?php echo $student['personal_email'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Institute Email</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="text" readonly  data-key="email" value="<?php echo $student['institute_email'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Phone</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="phone" value="<?php echo $student['phone'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="direction" value="<?php echo $student['direction'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-12" id="buttons-group">
                          <?php
                            if ($isMyUser) {
                              echo '<a id="btnEditProfile" class="btn btn-info" target="__blank">Edit</a> 
                              <button id="cancelButton" class="btn text-white btn-danger hidden">Cancel</button> 
                              <button id="saveButton" class="btn text-white btn-primary hidden">Save</button>
                              
                              <button id="LoadingButton" class="btn btn-primary hidden" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                              </button>';
                              
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
                          <h6 class="d-flex align-items-center mb-3">
                            <i class="material-icons text-info mr-2"></i>History</h6>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <div class="card bg-aux h-100">
                        <div class="card-body">
                          <h6 class="d-flex align-items-center mb-3"></h6>
                          
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
      let cancelButton = document.getElementById('cancelButton'); 
      let LoadingButton = document.getElementById('LoadingButton'); 
      
      btnEditProfile.addEventListener('click', function() {
        let inputs = document.querySelectorAll('.readonly-input');
        btnEditProfile.classList.add('hidden');
        inputs.forEach(input => {
          input.readOnly = false;
          input.classList.add('bg');
        });
        saveButton.classList.remove('hidden');
        cancelButton.classList.remove('hidden');
      })

      function hiddenEdit() {
        let inputs = document.querySelectorAll('.readonly-input');
        btnEditProfile.classList.remove('hidden');
        inputs.forEach(input => {
          input.readOnly = true;
          input.classList.remove('bg');
        });
        saveButton.classList.add('hidden');
        cancelButton.classList.add('hidden');
      }

      saveButton.addEventListener('click', function() {
        saveButton.classList.add('hidden');
        cancelButton.classList.add('hidden');
        LoadingButton.classList.remove('hidden');
            const inputs = document.querySelectorAll('input[data-key]');
            const dataKeyValues = Array.from(inputs).reduce((acc, input) => {
              acc[input.dataset.key] = input.value;
              return acc;
            }, {});

            console.log(dataKeyValues);
            
            fetch('/api/put/students/profile.php', {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(dataKeyValues)
            })
            .then(response => {
              if (!response.ok) {
                throw new Error('Error');
              }
              return response.json();
            })
            .then(data => {
              saveButton.classList.remove('hidden');
              LoadingButton.classList.add('hidden');
              console.log('Response:', data);
              hiddenEdit();
            })
            .catch(error => {
              console.error('error:', error);
            });
        });

      cancelButton.addEventListener('click', function () {
        hiddenEdit();
      })


      document.addEventListener('submit', function(event) {
        if (event.target.classList.contains('deletePhotoForm')) {
          event.preventDefault();
          
          const form = event.target;
          const filename = form.querySelector('input[name="filename"]').value;
          const account_number = form.querySelector('input[name="account_number"]').value;


          fetch('/api/delete/students/photo.php', {
              method: 'DELETE',
              body: new URLSearchParams({
                  account_number: account_number,
                  filename: filename
              })
          })
          .then(response => {
              if (!response.ok) {
                  throw new Error('Error request: ' + response.status);
              }
              return response.json();
          })
          .then(data => {
              alert(data.message);
              window.location.reload()
          })
          .catch(error => {
              console.error('Error:', error);
              alert('error: ' + error.message);
          });
        }
      
    });
    </script>
</body>
</html>
