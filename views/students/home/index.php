<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/homeStudents.css">
</head>
<body>
    <div class="main">
        <div class="header p-2 text-inverter bg-primary">
            <div class="flex justify-between">
                <div class="logo">LOGO</div>
                <div class="info text-center">
                    <h1>Welcome, User!</h1>
                    <p class="text-secundary text-xs">Lorem ipsum dolor sit amet</p>
                </div>
                <div class="noti">
                    <i class="bi bi-chat-dots"></i>
                </div>
            </div>
        </div>
        <div class="courses-container">
            <h5 class="text-md font-bold pt-4 pl-4">Running Course</h5>
            <div class="courses pl-4 pr-4">
                <div class="card card-course">
                    <div class="card-body">
                        <div class="name w-full rounded p-2 bg-primary text-white mb-1">
                            IS-100
                        </div>
                        <span class=" font-bold text-md mb-2">UX Design</span>
                        <div class="pr">
                            <div class="progress mt-1">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                            <p class="font-light text-xxs">Progress (75%)</p>
                        </div>
                    </div>
                </div>
                <div class="card card-course">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-success text-white mb-1">
                            IS-200
                        </div>
                        <span class="font-bold text-md mb-2">Illustration</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100" style="width: 47%"></div>
                        </div>
                        <p class="font-light text-xxs">Progress (47%)</p>
                    </div>
                </div>
                <div class="card card-course">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-warning text-white mb-1">
                            IS-300
                        </div>
                        <span class="font-bold text-md mb-2">3D Modeling</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
                        </div>
                        <p class="font-light text-xxs">Progress (30%)</p>
                    </div>
                </div>
                <div class="card card-course">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-orange-500 text-white mb-1">
                            IS-400
                        </div>
                        <span class="font-bold text-md mb-2">Programming</span>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-orange-500 progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
                        </div>
                        <p class="font-light text-xxs">Progress (20%)</p>
                    </div>
                </div>
                <div class="card card-course">
                    <div class="card-body">
                        <div class="name w-full rounded  p-2 bg-danger text-white mb-1">
                            IS-500
                        </div>
                        <span class="font-bold text-md mb-2">Software</span>
                        <div class="progress mt-1">
                            <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
                        </div>
                        <p class="font-light text-xxs">Progress (25%)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>