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
        <style>
            body, html {
                    height: 100%;
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa; /* Light gray background */
                }
                
                .center-box {
                    text-align: center;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                    width: 400px; /* Set a fixed width for consistent layout */
                }

                .center-box h1 {
                    font-size: 24px;
                    margin-bottom: 20px;
                    color: #333333;
                }

                .center-box input {
                    width: 100%;
                    padding: 10px;
                    margin-top: 10px;
                    font-size: 16px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }

                .center-box button {
                    margin-top: 15px;
                    padding: 10px;
                    width: 100%;
                    font-size: 16px;
                    background-color: #007bff;
                    border: none;
                    color: white;
                    border-radius: 5px;
                }

                .center-box button:hover {
                    background-color: #0056b3;
                }
        </style>
</head>
<body>

<div class="alert alert-danger" id="alertNoEmail" role="alert">
  No existe usuario con este correo intitucional
</div>

<div class="alert alert-success" id="sendEmail" role="alert">
  Revise su correo de recuperacion
</div>

<div class="spinner-border" role="status" id="loading">
  <span class="visually-hidden">Loading...</span>
</div>


    <div  id="formInput" class="center-box bg text">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo institucional</label>
            <input type="email" class="form-control" id="institucinalInputEmail" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo de recuperacion</label>
            <input type="email" class="form-control" id="personalInputEmail" aria-describedby="emailHelp">
    </div>
        <button type="submit" class="btn btn-primary" id="btnEnviar">Enviar</button>
    </div>

  
<script src="/public/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
<script src="/public/js/resetPasswordStuddent.js"></script>
</body>
</html>