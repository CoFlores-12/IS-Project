<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Students</title>
    <meta name="author" content="cofloresf@unah.hn">
        <meta name="version" content="0.1.0">
        <meta name="date" content="30/10/2014">
        <link rel="stylesheet" href="/public/css/theme.css">
        <link rel="icon" type="image/png" href="/public/images/logo.png" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
  
<?php include './../../../src/components/navbar.php'; ?>

<section class=" mt-4 flex justify-center items-center">
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
            <input type="email" id="email" class="form-control form-control-lg"
              placeholder="Enter a email address" />
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-3">
            <input type="password" id="password" class="form-control form-control-lg"
              placeholder="Enter password" />
          </div>

          <div class="flex justify-between items-center">
            
            <a href="/views/students/login/resetHome.php" class="text">Forgot password?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
              <button id="loginButton" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>
<script src="/public/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
<script>
  const loginButton = document.getElementById('loginButton');
  loginButton.addEventListener('click', function(){
    loginButton.disabled = true;
    loginButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;
    fetch('/api/post/students/login.php', {
        method: 'POST',
        body: new URLSearchParams({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    })
    .then(async response => {
        if (!response.ok) {
            let text = await response.text();
            
            throw new Error('Error in request: ' + text);
        }
        return response.json();
    })
    .then(data => {
        location.href = data.route;
    })
    .catch(error => {
        loginButton.disabled = false;
        loginButton.innerHTML = 'Login';
        alert('error: ' + error.message);
    });
    
  })
</script>
</body>
</html>