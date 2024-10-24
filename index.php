<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/theme.css">

    <style>
        .header{
            background-color: var(--primary-color);
            height: 70%;
        }
        .banner {
            padding-top: 10%;
        }
        .svg {
            fill: var(--primary-color);
            position: relative;
            z-index: -1;
        }
        .active {
            color: var(--accent-color) !important;
        }
        main { 
            height: 100%;
        }
        .nav {
            position: fixed;
            width: 100%;
            box-sizing: border-box;
            z-index: 9999;
            background-color: var(--primary-color);
        }
      
    </style>
</head>
<body>
    <main>
        <div class="nav p-4 flex justify-between items-center">
            <a class="text-3xl font-bold text-white" href="/">
                <!-- <img class="h-9" src="logo.png" alt="logo"> -->
                Logo
            </a>
            <div class="flex-1 justify-center items-center flex">
                <a class="text-white active" href="/">Home</a>
                <a class="text-white space-x-12" href="/">Admissions</a>
                <a class="text-white space-x-12" href="/">Students</a>
                <a class="text-white space-x-12" href="/">Administration</a>
            </div>
            <a class="text-white px-4 py-2 bg-blue font-bold rounded" href="#">
                Login
            </a>
        </div>

        <div class="header relative z-10">
            <div class="banner z-10 flex justify-between p-4 box-sizing-border">
                <div class="info text-center flex-1">
                    <h1>Started</h1>
                </div>
                <div class="flex-1 flex justify-content">
                    <img width="90%" class="max-w-90" src="/public/images/edu.png" alt="">
                </div>
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path class="svg" fill-opacity="1" d="M0,96L80,96C160,96,320,96,480,122.7C640,149,800,203,960,192C1120,181,1280,107,1360,69.3L1440,32L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path></svg>
        <div class="h-full">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos recusandae doloribus totam quis laudantium earum rem molestiae, voluptate blanditiis alias! Laudantium nobis ducimus, consequuntur ut suscipit dicta quam cupiditate doloremque.</div>
        <div class="h-full">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore sequi eius perferendis dolorum iusto sapiente, modi magni eveniet nesciunt alias, ipsum amet praesentium quidem nostrum et consectetur expedita! Vitae, eius.</div>
        <div class="h-full"></div>
        </main>
</body>
</html>