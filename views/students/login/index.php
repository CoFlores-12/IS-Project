<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Students</title>
    
        <link rel="stylesheet" href="/public/css/theme.css">
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<section class="h-full flex justify-center items-center">
  <div class="container-fluid">
    <div class="row flex justify-center items-center h-full">
      <div class="col-md-9 col-lg-6 col-xl-5 flex justify-center items-center mb-4">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
          class="img-fluid" width="70%" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-4 col-xl-4 offset-xl-1">
        <form>


          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form3Example3" class="form-control form-control-lg"
              placeholder="Enter a email address" />
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" id="form3Example4" class="form-control form-control-lg"
              placeholder="Enter password" />
          </div>

          <div class="flex justify-between items-center">
            
            <a href="#!" class="text">Forgot password?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <a href="/views/students/home/index.php">
              <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>
<script src="/public/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>