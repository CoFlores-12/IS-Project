<?php 
$current_page = $_SERVER['REQUEST_URI'];
?>
<style>
    .nav-link.active {
    font-weight: bold;
    color: #000; /* Puedes personalizar el color */
}

</style>
<nav class="navbar navbar-expand-lg">
    <div class="container px-5">
        <a class="navbar-brand" href="/"><img height="32px" src="/public/images/logo.png" alt="Logo"></a>
        <button class="navbar-toggler text" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == '/' ? 'active' : ''; ?>" href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_page, '/views/admissions/form/index.php') !== false ? 'active' : ''; ?>" href="/views/admissions/form/index.php">Admisiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_page, '/views/students/login/index.php') !== false ? 'active' : ''; ?>" href="/views/students/login/index.php">Estudiantes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_page, '/views/admin/login/index.php') !== false ? 'active' : ''; ?>" href="/views/admin/login/index.php">Administración</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
