<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
                <div class="container px-5">
                    <a class="navbar-brand" href="index.html">LOGO</a>
                    <button class="navbar-toggler text" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="bi bi-list"></i></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="/views/admissions/form/index.php">Admissions</a></li>
                            <li class="nav-item"><a class="nav-link" href="/views/students/login/index.php">Students</a></li>
                            <li class="nav-item"><a class="nav-link" href="/">Administration</a></li>
                            
                        </ul>
                    </div>
                </div>
            </nav>
    <div class="main min-h-full flex bg-aux justify-center items-center p-4">
        
        <form class="needs-validation bg rounded p-4" novalidate method="POST" action="/api/post/admissions/form.php"  enctype="multipart/form-data">
            <div class="form-row flex">
                <div class="col mb-3">
                    <label for="validationCustom01">First name</label>
                    <input name="name" type="text" class="form-control" id="validationCustom01" placeholder="First name"  required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <label for="validationCustom02">Last name</label>
                    <input name="lastName" type="text" class="form-control" id="validationCustom02" placeholder="Last name" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <label for="mainCareer">Select main career</label>
                    <select name="mainCareer" class="form-control" id="mainCareer">
                        <option value="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="secondaryCareer">Select secondary career</label>
                    <select name="secondaryCareer" class="form-control  form-control-sm" id="secondaryCareer">

                        <option value="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <label for="identity">identity</label>
                    <input name="identity" type="text" class="form-control" id="identity" placeholder="XXXX-XXXX-XXXXX" maxlength="15" required>
                </div>
            </div>
            <div class="form-row flex">
                <div class="col mb-3">
                    <label for="phone">Phone</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="XXXX-XXXX" maxlength="9" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <label for="validationCustom02">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="email@example.com" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group row">
                    <label for="certify" class="col">High school certificate photo.</label>
                    <div class="col">
                        <input name="certify" type="file" class="form-control-file" id="certify" required>
                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <label for="regionalCenter">Select regional center</label>
                    <select name="regionalCenter" class="form-control custom-select" id="regionalCenter">
                        <option value="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Submit form</button>
        </form>

    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
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


       

        document.getElementById('phone').addEventListener('input', function (e) {
            let input = e.target;
            let value = input.value.replace(/\D/g, '');
            if (value.length > 0 && !/[389]/.test(value[0])) {
                value = '';
            }

            let formattedValue = '';
            if (value.length > 0) {
                formattedValue = value.substring(0, 4);
            }
            if (value.length > 4) {
                formattedValue += '-' + value.substring(4, 8);
            }

            input.value = formattedValue;
        });

        document.getElementById('identity').addEventListener('input', function (e) {
            let input = e.target;
            let value = input.value.replace(/\D/g, ''); 
            let formattedValue = '';

            if (value.length > 0) {
                formattedValue = value.substring(0, 4); 
            }
            if (value.length > 4) {
                formattedValue += '-' + value.substring(4, 8); 
            }
            if (value.length > 8) {
                formattedValue += '-' + value.substring(8, 13); 
            }

            input.value = formattedValue;
        });

        document.getElementById('email').addEventListener('input', function (e) {
            const emailInput = e.target;
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (emailPattern.test(emailInput.value)) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
            } else {
                emailInput.classList.remove('is-valid');
                emailInput.classList.add('is-invalid');
            }
        });
    }, false);
    })();

    
    </script>
</body>
</html>