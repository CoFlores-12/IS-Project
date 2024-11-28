<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña - Estudiante</title>
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

    <div  class="center-box bg-aux text" hidden id="formInput">
        <div>
            <input type="text" placeholder="Ingrese la nueva Contreseña" id="inputPassword" required>
            <button id="submit-button" type="submit">Enviar</button>
        </div>
    </div>

    <div  id="alertExpired" hidden  class="alert alert-danger" role="alert">
        El token Expiro
    </div>

    <div id="alertSuccess" hidden class="alert alert-success" role="alert">
        Contraseña Cambiada
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/resestPasswordS.js"></script>
</body>
</html>