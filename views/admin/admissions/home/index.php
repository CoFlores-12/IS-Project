<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Admissions';

AuthMiddleware::checkAccess($requiredRole);
include './../../../../src/modules/database.php';

$db = (new Database())->getConnection();

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Applicant WHERE status_id = 0");
$applicant = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Students");
$students = $result->fetch_assoc()['count'];

$result = $db->execute_query("SELECT COUNT(*) AS count FROM Applicant WHERE status_id = 1");
$admitted = $result->fetch_assoc()['count'];
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
        #applicantTable,#tableSections, #tableWaitList{
            width: 100%;
        }
        td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}
        .selected {
            background-color: var(--primary-color);
            color: #FFF;
        }
    </style>
</head>
<body>

<!-- Modal Admitted -->
<div class="modal fade" id="admittedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Admitteds</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <a class="btn text-success btn-outline-success" href="/api/get/admin/admittedStudents.php">
        Export Admitteds <i class="bi bi-arrow-bar-up"></i>
    </a>
    </div>
    </div>
  </div>
</div>
<!-- Modal Admitted -->

<!-- Modal Applicant -->
<div class="modal fade" id="applicantModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Applicants</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="filters row align-items-center">
            <div class="col-3">
                <label for="" >Filter By:</label>
            </div>
            <div class="col">
                <select class="form-control" name="" id="filterCareer">
                    <option value="">Career</option>
                </select>
            </div>
            <div class="col">
                <select class="form-control" name="" id="filterExam">
                    <option value="">Exam</option>
                </select>
            </div>
        </div>
        <table id="applicantTable" class="my-2">
            <thead>
                <tr>
                    <td>Identity</td>
                    <td>Full name</td>
                    <td>1st Career</td>
                    <td>2nd Career</td>
                    <td>Examns</td>
                </tr>
            </thead>
           <tbody id="aspTableBody">
           </tbody>
            
        </table>
    </div>
    
    </div>
  </div>
</div>
<!-- Modal Applicant -->

<!-- Modal Add Exam -->
<div class="modal fade" id="addExamModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Add Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <datalist id="examnsToCareers">
      </datalist>
      <div class="modal-body" id="addExamnModalBody">
        <center><div class="spinner-grow text" role="status"></div></center>
        
    </div>
    </div>
  </div>
</div>
<!-- Modal Add Exam -->

    <!-- Modal CSV Upload -->
    <div class="modal fade" id="csvUploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="staticBackdropLabel">Upload a CSV with the admissions results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="csvUploadForm" method="post" enctype="multipart/form-data">
                        <label for="file">Select a CSV file:</label>
                        <input type="file" name="file" id="file" accept=".csv" required>
                        <button type="submit" name="submit" class="btn btn-primary mt-2">Upload and Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Result Message -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="successModalLabel">Upload Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                    <button id="nextActionBtn" class="btn btn-primary">Validate Applicant Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para éxito de validación -->
    <div class="modal fade" id="finalSuccessModal" tabindex="-1" aria-labelledby="finalSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="finalSuccessModalLabel">Task Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>The validation of admission results has been successful.</p>
                    <button id="goToNextTask" class="btn btn-success">E-mail Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
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
                
                <button id="addExamnBtn" class="w-full bg-aux text btn rounded">Add Exam</button>
                <button id="csvUploadBtn" class="w-full bg-aux text btn rounded mt-3" data-bs-toggle="modal" data-bs-target="#csvUploadModal">Upload Exam Results</button>
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
               
            </div>
            <div class="row p-4">
                <div class="col">
                    <div id="cardApplicant" class="card bg-aux shadow rounded m-2  p-2" data-bs-toggle="modal" data-bs-target="#applicantModal">
                        <span>Applicants</span>
                        <strong><?php echo $applicant; ?></strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>Students</span>
                        <strong><?php echo $students; ?></strong>
                    </div>
                </div>
                <div class="col">
                    <div id="cardAdmitted" class="card shadow rounded m-2 bg-aux p-2" data-bs-toggle="modal" data-bs-target="#admittedModal">
                        <span>Admitted</span>
                        <strong><?php echo $admitted; ?></strong>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow rounded m-2 bg-aux p-2">
                        <span>...</span>
                        <strong>-</strong>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/csvUploadForm.js"></script>
    <script src="/public/js/applicantResultValidation.js"></script>
    <script src="/public/js/emailApplicantResult.js"></script>
    <script>
        const filterCareer = document.getElementById('filterCareer');
        const filterExam = document.getElementById('filterExam');
        const aspTableBody = document.getElementById('aspTableBody');
        const Careers = [];
        const Exams = [];
        document.querySelector('#cardApplicant').addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('applicantModal'));
            modal.show();
            fetch('/api/get/admin/applicants.php')
            .then((response)=>{return response.json()})
            .then((response)=>{
                console.log(response);
                
                aspTableBody.innerHTML = ''
                response.Asp.reverse().forEach(element => {
                    const examsForApplicant = response.Exams.filter(exam =>
                        exam.career_id === element.preferend_career_id ||
                        exam.career_id === element.secondary_career_id
                    );

                    const examCodes = examsForApplicant.map(exam => {
                        if (!Exams.includes(exam.exam_code)) {
                            Exams.push(exam.exam_code);
                        }
                        
                        return exam.exam_code
                    }).join(',');

                    if (!Careers.includes(element.preferend_career_name)) {
                        Careers.push(element.preferend_career_name)
                    }
                    if (!Careers.includes(element.secondary_career_name)) {
                        Careers.push(element.secondary_career_name)
                    }
                    aspTableBody.innerHTML += `<tr>
                                <td>${element.identity}</td>
                                <td>${element.full_name}</td>
                                <td>${element.preferend_career_name}</td>
                                <td>${element.secondary_career_name}</td>
                                <td>${examCodes}</td>
                            </tr>`
                });
                filterCareer.innerHTML = '<option>Career</option>'
                Careers.forEach(element => {
                    filterCareer.innerHTML += `<option>${element}</option>`
                });
                filterExam.innerHTML = '<option>Exams</option>'
                Exams.forEach(element => {
                    filterExam.innerHTML += `<option>${element}</option>`
                });
                
            })

        });

        document.getElementById('filterExam').addEventListener('change', filterByExam);
document.getElementById('filterCareer').addEventListener('change', filterByCareer);

function filterByExam() {
    const selectedExam = document.getElementById('filterExam').value.trim();
    const rows = document.querySelectorAll('#aspTableBody tr');
    
    rows.forEach(row => {
        if (selectedExam == 'Exams') {
            row.style.display = ''; 
            return;
        }
        const examCell = row.cells[4].textContent.trim(); 
        
        if (!selectedExam || examCell.includes(selectedExam)) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none';
        }
    });
    filterByCareer
}

function filterByCareer() {
    const selectedCareer = document.getElementById('filterCareer').value.trim();
    const rows = document.querySelectorAll('#aspTableBody tr');
    
    rows.forEach(row => {
        if (selectedCareer == 'Career') {
            row.style.display = '';
            return;
        }
        const preferedCareer = row.cells[2].textContent.trim(); 
        const secondaryCareer = row.cells[3].textContent.trim(); 
        
        if (!selectedCareer || 
            preferedCareer.includes(selectedCareer) || 
            secondaryCareer.includes(selectedCareer)) {
            row.style.display = '';
        } else {
            row.style.display = 'none'; 
        }
    });
}



        document.querySelector('#cardAdmitted').addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('admittedModal'));
            modal.show();
        });
        document.querySelector('#addExamnBtn').addEventListener('click', function () {
            var modalAddExam = new bootstrap.Modal(document.getElementById('addExamModal'));
            modalAddExam.show();
            fetch('/api/get/public/examsAndCareers.php')
            .then((response)=>{return response.json()})
            .then((response)=>{
                
                const addExamnModalBody = document.getElementById('addExamnModalBody');
                const examnsToCareers = document.getElementById('examnsToCareers');

                let HTML = `<input type="text" list="examnsToCareers" class="form-control" placeholder="Examn">
        <select name="" id="CareerNewExamn" class="form-control my-4">
            <option value="">Select Career...</option>`;
                response['Careers'].forEach(career => {
                    HTML += `<option value="${career.career_id}">${career.career_name}</option>`
                });
                examnsToCareers.innerHTML = '';
                response['Exams'].forEach(exam => {
                    examnsToCareers.innerHTML+= `<option>${exam.exam_code}</option>`
                });
                HTML += ` </select><input class="form-control my-4" type="number" name="" id="passingScore" placeholder="passing score">
        <button id="addExamnBtn" class="w-full btn bg-custom-primary text-white">Add Exam</button>`;
                addExamnModalBody.innerHTML = HTML;

                document.getElementById('addExamnBtn').addEventListener('click', (e)=>{
                    e.target.innerHTML = '<div class="spinner-grow text" role="status"></div>';
                    e.target.disabled = true;

                    const exam = document.querySelector('input[placeholder="Examn"]').value;
                    const careerId = document.getElementById('CareerNewExamn').value;
                    const passingScore = document.getElementById('passingScore').value;

                    const formData = {
                        exam: exam,
                        career_id: careerId,
                        passing_score: passingScore
                    };

                    fetch('/api/post/admin/addExamToCareer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response =>{
                        alert('Added!')
                        modalAddExam.hide();
                    }) 
                })
            })
        });

        
    
    </script>
</body>
</html>
