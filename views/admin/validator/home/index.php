<?php 
require_once '../../../../src/modules/Auth.php';

$requiredRole = 'Validator';

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
  <div class="modal-dialog modal-dialog-centered dialog-modal-scrollable">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Validation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body" id="dataModalValidate">
            <center><div class="spinner-border text-secondary" role="status"></div></center>
            
        </div>
                
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="invalidBtn" disabled>Invalid</button>
            <button type="submit" class="btn btn-success" id="validBtn" disabled>Valid</button>
        </div>
    </div>
  </div>
</div>
<!-- Modal New User -->


<div class="main h-full flex flex-column">
        
        <div class="header p-2 text-inverter bg">
            <div class="flex justify-between align-items-center">
                <h4 class="text">Validator</h4>
                
                <div class="btn-group">
                    <button type="button" class="btn bg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img height="40px" width="40px" src='https://avataaars.io/?avatarStyle=Circle&topType=LongHairStraight&accessoriesType=Blank&hairColor=BrownDark&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light'/>
                        
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/api/get/logout.php">Logout <i class="bi bi-box-arrow-right"></i></a></li>
                    </ul>
                 </div>
            </div>
        </div>
        <div class="container-fluid flex-grow-1">
            <div class="flex h-full justify-content-center align-items-center">
                <button id="addUserBtn" class="btn bg-custom-primary text-white">start validation</button>
       
            </div>
         </div>
    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
       const addUserBtn = document.getElementById('addUserBtn');
       const newUserModal = document.getElementById('newUserModal');
       const applicant_id = document.getElementById('applicant_id');
       const validBtn = document.getElementById('validBtn');
       const invalidBtn = document.getElementById('invalidBtn');

       const person_id = document.getElementById('person_id');
       const full_name = document.getElementById('full_name');
       const certify = document.getElementById('certify');
       const dataMdalValidate = document.getElementById('dataModalValidate');
       const newUserModalBS = new bootstrap.Modal(newUserModal);

       function getAsppirant() {
        fetch('/api/get/admin/applicantNoValidate.php')
            .then((res)=>{return res.json()})
            .then((res)=>{
                try {
                    dataMdalValidate.innerHTML = `
                    <input class="form-control my-2" type="hidden" name="" value="${res.applicant_id}" id="applicant_id">
                    <input class="form-control my-2" type="text" name="" value="${res.person_id}" disabled id="person_id">
                    <input class="form-control my-2" type="text" name="" value="${res.full_name}" disabled id="full_name">
                    <img width="100%" class="my-2" src="${res.certify}" id="certify" alt="">
                    `;
                    validBtn.disabled = false;
                    invalidBtn.disabled = false;
                } catch (error) {
                    dataMdalValidate.innerHTML = `<div class="alert alert-warning" role="alert">
  no applicants
</div>`
                }
            })
       }
       addUserBtn.addEventListener('click', ()=>{
            newUserModalBS.show();
            getAsppirant();
       })

       validBtn.addEventListener('click', (e)=>{
            e.target.innerHTML = `<div class="spinner-border text-secondary" role="status"></div>`;
            e.target.disabled = true;
            validate(1)
            .then((res)=>{return res.json()})
           .then((res)=>{
            e.target.innerHTML = `Valid`;
            e.target.disabled = false;
            getAsppirant();
           })
        })
        invalidBtn.addEventListener('click', (e)=>{
           e.target.innerHTML = `<div class="spinner-border text-secondary" role="status"></div>`;
           e.target.disabled = true;
           validate(0)
           .then((res)=>{return res.json()})
           .then((res)=>{
               e.target.innerHTML = `Invalid`;
               e.target.disabled = false;
               getAsppirant();
           })
       })

       async function validate(validateBIT) {
           const formData = new FormData();
           formData.append("applicant_id", document.getElementById('applicant_id').value)
           formData.append("validate", validateBIT)
           dataMdalValidate.innerHTML = ' <center><div class="spinner-border text-secondary" role="status"></div></center>'
           const reques = await fetch('/api/put/admin/validateAspirant.php', {
               method: 'POST',
               body: formData
            });
            return reques
       }
    </script>
</body>
</html>
