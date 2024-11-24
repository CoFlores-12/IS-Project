<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="author" content="cofloresf@unah.hn">
        <meta name="version" content="0.3.0">
        <meta name="dateCreated" content="29/10/2014">
        <meta name="dateUpdated" content="23/11/2014">
        <meta name="description" content="Página de inicio del Proyecto IS">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Proyecto IS</title>
        <link rel="stylesheet" href="/public/css/theme.css">
        <link rel="icon" type="image/png" href="/public/images/logo.png" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">

        <style>            
            .feature {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 3rem;
                width: 3rem;
                font-size: 1.5rem;
            }

            .bg-featured-blog {
                height: 100%;
                width: 100%;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 15rem;
            }
            .card {
                background-color: var(--bg-aux) !important;
                color: var(--text) !important;
            }
        </style>
    </head>
    <body class="d-flex flex-column h-100">
        <main class="flex-shrink-0">
        <?php include './src/components/navbar.php'; ?>
            <!-- Encabezado-->
            <header class="py-5">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center justify-content-center">
                        <div class="col-lg-8 col-xl-7 col-xxl-6">
                            <div class="my-5 text-center text-xl-start">
                                <h1 class="display-5 fw-bolder mb-2">Transforma tu futuro con educación de calidad</h1>
                                <p class="lead fw-normal mb-4">Descubre programas académicos de excelencia, docentes expertos y una comunidad comprometida con tu éxito.</p>
                                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                                    <a class="btn bg-custom-primary text-white btn-lg px-4 me-sm-3" href="/views/students/login/index.php">Comenzar</a>
                                    <a class="btn btn-outline btn-lg px-4" href="#features">Saber más</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                            <img class="img-fluid rounded-3 my-5" src="/public/images/edu.png" alt="Educación de calidad" /></div>
                    </div>
                </div>
            </header>
            <!-- Sección de características-->
            <section class="py-5" id="features">
                <div class="container px-5 my-5">
                    <div class="row gx-5">
                        <div class="col-lg-4 mb-5 mb-lg-0"><h2 class="fw-bolder mb-0">Una mejor forma de comenzar a construir.</h2></div>
                        <div class="col-lg-8">
                            <div class="row gx-5 row-cols-1 row-cols-md-2">
                                <div class="col mb-5 h-100">
                                    <div class="feature bg-custom-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-collection"></i></div>
                                    <h2 class="h5">Título destacado</h2>
                                    <p class="mb-0 text-secundary">Párrafo de texto debajo del título para explicar el encabezado. Aquí hay un poco más de texto.</p>
                                </div>
                                <div class="col mb-5 h-100">
                                    <div class="feature bg-custom-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-building"></i></div>
                                    <h2 class="h5">Título destacado</h2>
                                    <p class="mb-0 text-secundary">Párrafo de texto debajo del título para explicar el encabezado. Aquí hay un poco más de texto.</p>
                                </div>
                                <div class="col mb-5 mb-md-0 h-100">
                                    <div class="feature bg-custom-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                                    <h2 class="h5">Título destacado</h2>
                                    <p class="mb-0 text-secundary">Párrafo de texto debajo del título para explicar el encabezado. Aquí hay un poco más de texto.</p>
                                </div>
                                <div class="col h-100">
                                    <div class="feature bg-custom-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                                    <h2 class="h5">Título destacado</h2>
                                    <p class="mb-0 text-secundary">Párrafo de texto debajo del título para explicar el encabezado. Aquí hay un poco más de texto.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Sección de vista previa del blog-->
            <section class="py-5">
                <div class="container px-5 my-5">
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-8 col-xl-6">
                            <div class="text-center">
                                <h2 class="fw-bolder">Desde nuestro blog</h2>
                                <p class="lead fw-normal text-secundary mb-5 text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-5">
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" src="https://dummyimage.com/600x350/ced4da/6c757d" alt="..." />
                                <div class="card-body p-4">
                                    <a class="text-decoration-none link-dark stretched-link" href="#!"><h5 class="card-title text mb-3">Titulo</h5></a>
                                    <p class="card-text mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="small">
                                                <div class="text-secundary">Oct 22, 2024</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" src="https://dummyimage.com/600x350/adb5bd/495057" alt="..." />
                                <div class="card-body p-4">
                                    <a class="text-decoration-none link-dark stretched-link" href="#!"><h5 class="card-title text mb-3">Titulo</h5></a>
                                    <p class="card-text mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="small">
                                                <div class="text-secundary">Oct 23, 2024</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" src="https://dummyimage.com/600x350/6c757d/343a40" alt="..." />
                                <div class="card-body p-4">
                                    <a class="text-decoration-none link-dark stretched-link" href="#!"><h5 class="card-title text mb-3">Titulo</h5></a>
                                    <p class="card-text mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="small">
                                                <div class="text-secundary">Oct 25, 2023</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- Pie de página-->
        <footer class="py-4 mt-auto bg-aux">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0">Copyright &copy; Proyecto IS 2024</div></div>
                    <div class="col-auto">
                        <a class="link text small" href="#!">Privacidad</a>
                        <span class="text mx-1">&middot;</span>
                        <a class="link text small" href="#!">Términos</a>
                        <span class="text mx-1">&middot;</span>
                        <a class="link text small" href="#!">Contacto</a>
                    </div>
                </div>
            </div>
        </footer>
        
        <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    </body>
</html>
