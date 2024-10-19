<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IS Project</title>
    <style>
        html, body {
            height: 100%;
        }
        *{
            margin: 0;
        }
        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: changeColor 3s infinite;
            animation-timing-function: cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        @keyframes changeColor {
            0% {
                color: black;
                background-color: white;
            }
            50% {
                color: white;
                background-color: black;
            }
            100% {
                color: black;
                background-color: white;
            }
        }
        .loader {
            font-size: 2em;
            font-weight: 900;
            font-family: sans-serif;
        }
        .loader span {
            display: inline-flex;
        }
        .loader span:nth-child(2) {
            letter-spacing: -1em;
            overflow: hidden;
            animation: reveal 1500ms cubic-bezier(0.645, 0.045, 0.355, 1) infinite
            alternate;
            animation-delay: 2S;
        }
        @keyframes reveal {
            0%,
            100% {
                opacity: 0.5;
                letter-spacing: -1em;
            }
            50% {
                opacity: 1;
                letter-spacing: 0em;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="loader">
            <span>&lt;</span>
            <span>COMING SOON</span>
            <span>/&gt;</span>
        </div>
    </div>
</body>
</html>