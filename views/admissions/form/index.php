<?php
include './../../../src/modules/database.php';
date_default_timezone_set('America/Tegucigalpa');
$db = (new Database())->getConnection();
$regionalCenters = $db->execute_query("SELECT * FROM Regional_center");
$sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.registrationPeriod')) as RP
        FROM Config
        WHERE config_id = 1;";
$times = $db->execute_query($sql,[]);
$result = $times->fetch_assoc();
$data = json_decode($result['RP'], true);

$startTime = new DateTime($data['startTime']);
$endTime = new DateTime($data['endTime']);
$currentTime = new DateTime();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.2.0">
    <meta name="date" content="29/10/2014">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <?php include './../../../src/components/navbar.php'; 
    
    if ($currentTime < $startTime || $currentTime > $endTime) {
        echo '<div class="alert alert-warning text-center m-4" role="alert">
  non-active registration period
</div>' ;
        return;
    }
    
    ?>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <center class="w-full">
                <div class="spinner-border" role="status">
                </div>
            </center>
        </div>
    </div>

    <!-- Formulario principal -->
    <div class="main min-h-full flex bg-aux justify-center items-center p-4">
    <form id="admissionForm" class="needs-validation bg rounded p-4" novalidate>
    <div class="form-row d-flex gap-4">
        <div class="col mb-3">
            <label for="name">Nombres</label>
            <input name="name" type="text" class="form-control" id="name" placeholder="Ejemplo: Juan" required 
                   aria-label="Campo para ingresar su nombre completo" aria-required="true" 
                   aria-describedby="nameFeedback">
            <div id="nameFeedback" class="invalid-feedback">Por favor ingrese su nombre.</div>
        </div>
        <div class="col mb-3">
            <label for="lastName">Apellidos</label>
            <input name="lastName" type="text" class="form-control" id="lastName" placeholder="Ejemplo: Pérez" required 
                   aria-label="Campo para ingresar sus apellidos" aria-required="true" 
                   aria-describedby="lastNameFeedback">
            <div id="lastNameFeedback" class="invalid-feedback">Por favor ingrese sus apellidos.</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="form-group">
            <label for="identity">Número de identidad</label>
            <input name="identity" id="identity" type="text" class="form-control" placeholder="123456789" maxlength="15" required 
                   aria-label="Campo para ingresar su número de identidad" aria-required="true" 
                   aria-describedby="identityFeedback">
            <div id="identityFeedback" class="invalid-feedback">Por favor ingrese su número de identidad.</div>
        </div>
    </div>

    <div class="form-row d-flex gap-4">
        <div class="col-3 mb-3">
            <label for="phone">Número de teléfono</label>
            <input name="phone" id="phone" type="text" class="form-control" placeholder="987654321" maxlength="9" required 
                   aria-label="Campo para ingresar su número de teléfono" aria-required="true" 
                   aria-describedby="phoneFeedback">
            <div id="phoneFeedback" class="invalid-feedback">Por favor ingrese un número de teléfono válido.</div>
        </div>
        <div class="col mb-3">
            <label for="email">Correo electrónico</label>
            <input name="email" id="email" type="email" class="form-control" placeholder="correo@ejemplo.com" required 
                   aria-label="Campo para ingresar su correo electrónico" aria-required="true" 
                   aria-describedby="emailFeedback">
            <div id="emailFeedback" class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
        </div>
    </div>

    <div class="row mt-4 mb-2">
        <div class="form-group">
            <label for="regionalCenter">Centro regional</label>
            <select name="regionalCenter" class="form-control" id="regionalCenter" required 
                    aria-label="Seleccione su centro regional" aria-required="true" 
                    aria-describedby="regionalCenterFeedback">
                <option value="">Seleccione un centro regional</option>
                <?php while ($center = $regionalCenters->fetch_assoc()): ?>
                    <option value="<?php echo $center['center_id']; ?>"><?php echo $center['center_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <div id="regionalCenterFeedback" class="invalid-feedback">Por favor seleccione un centro regional.</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="form-group">
            <label for="mainCareer">Carrera principal</label>
            <select name="mainCareer" class="form-control" id="mainCareer" disabled required 
                    aria-label="Seleccione su carrera principal" aria-required="true" 
                    aria-describedby="mainCareerFeedback">
                <option value="">Seleccione una carrera principal</option>
            </select>
            <div id="mainCareerFeedback" class="invalid-feedback">Por favor seleccione una carrera principal.</div>
        </div>
        <div class="form-group mt-2">
            <label for="secondaryCareer">Carrera secundaria</label>
            <select name="secondaryCareer" class="form-control" id="secondaryCareer" disabled required 
                    aria-label="Seleccione su carrera secundaria" aria-required="true" 
                    aria-describedby="secondaryCareerFeedback">
                <option value="">Seleccione una carrera secundaria</option>
            </select>
            <div id="secondaryCareerFeedback" class="invalid-feedback">Por favor seleccione una carrera secundaria.</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="form-group row mt-2">
            <label for="certify" class="col-3">Certificado de estudios:</label>
            <div class="col">
                <input name="certify" accept="image/*" type="file" class="form-control-file" id="certify" required 
                       aria-label="Subir su certificado de estudios" aria-required="true" 
                       aria-describedby="certifyFeedback">
                <div id="certifyFeedback" class="invalid-feedback">Por favor cargue su certificado de estudios.</div>
            </div>
        </div>
    </div>

    <button class="btn bg-custom-primary form-control text-white" type="submit">Aplicar</button>
</form>


    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/formAdmissions.js"></script>
</body>
</html>
